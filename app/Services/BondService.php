<?php

namespace App\Services;

use App\Helpers\TextHelper;
use App\Models\Bond;
use App\Models\BondDocument;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\EmployeeDocument;
use App\Models\User;
use App\Models\UserType;
use App\Notifications\BondImpededNotification;
use App\Notifications\NewBondNotification;
use App\Notifications\NewRightsNotification;
use App\Notifications\RequestReviewNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class BondService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        (new Bond())->logListed();

        $query = Bond::with(['employee', 'course', 'role', 'pole']);
        $query = $query->AcceptRequest(Bond::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);

        $bonds = $query->paginate(10);
        $bonds->withQueryString();

        return $bonds;
    }

    /**
     * Undocumented function
     *
     * @param array<string, string> $attributes
     *
     * @return Bond
     */
    public function create(array $attributes): ?Bond
    {
        $attributes['volunteer'] = isset($attributes['volunteer']);
        $attributes['terminated_at'] = null;
        $attributes['impediment'] = true;
        $attributes['impediment_description'] = 'Vínculo ainda não revisado';
        $attributes['uaba_checked_at'] = null;

        $bond = null;

        DB::transaction(static function () use ($attributes, &$bond) {
            $bond = Bond::create($attributes);

            $bond->qualification()->create([
                'knowledge_area' => $attributes['knowledge_area'],
                'course_name' => TextHelper::titleCase($attributes['course_name']),
                'institution_name' => TextHelper::titleCase($attributes['institution_name']),
            ]);

            $employeeDocuments = EmployeeDocument::where('employee_id', $bond->employee_id)->get();
            foreach ($employeeDocuments as $employeeDocument) {
                $bondDocument = new BondDocument();
                $bondDocument->bond_id = $bond->id;
                $bondDocument->save();

                $newDocument = new Document();
                $newDocument->original_name = $employeeDocument->document?->original_name;
                $newDocument->file_data = $employeeDocument->document?->file_data;
                $newDocument->document_type_id = $employeeDocument->document?->documentType?->id;
                $newDocument->documentable_type = \App\Models\BondDocument::class;
                $newDocument->documentable_id = $bondDocument->id;
                $newDocument->save();
            }

            //Notify grantor assistants
            $ass_UT = UserType::firstWhere('acronym', 'ass');
            $coordOrAssistants = User::where('active', true)->whereActiveUserType($ass_UT?->id)->get();
            Notification::send($coordOrAssistants, new NewBondNotification($bond));
        });

        return $bond;
    }

    /**
     * Undocumented function
     *
     * @param Bond $bond
     *
     * @return Bond
     */
    public function read(Bond $bond): Bond
    {
        $bond->logFetched();

        return $bond;
    }

    /**
     * Undocumented function
     *
     * @param array<string, string> $attributes
     * @param Bond $bond
     *
     * @return Bond
     */
    public function update(array $attributes, Bond $bond): Bond
    {
        $attributes['volunteer'] = isset($attributes['volunteer']);

        $bond->update($attributes);

        $bond->qualification()->updateOrCreate([], [
            'knowledge_area' => $attributes['knowledge_area'],
            'course_name' => TextHelper::titleCase($attributes['course_name']),
            'institution_name' => TextHelper::titleCase($attributes['institution_name']),
        ]);

        return $bond;
    }

    /**
     * Undocumented function
     *
     * @param Bond $bond
     *
     * @return void
     */
    public function delete(Bond $bond)
    {
        DB::transaction(static function () use ($bond) {
            foreach ($bond->bondDocuments as $document) {
                $document->delete();
            }

            $bond->qualification()->delete();

            $bond->delete();
        });
    }

    /**
     * Undocumented function
     *
     * @param array<string, string> $attributes
     * @param Bond $bond
     *
     * @return Bond
     */
    public function review(array $attributes, Bond $bond): Bond
    {
        //get impediment; check if bond have 'termo'; if not, impediment = true.
        $attributes['impediment'] = $attributes['impediment'] === '1';
        //$bond->impediment_description = $attributes['impediment_description'];
        $attributes['uaba_checked_at'] = now();

        $termo_document_type_id = DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first()?->id;
        $termo_document_count = Document::where('document_type_id', $termo_document_type_id)
            ->whereHasMorph('documentable', \App\Models\BondDocument::class, static function ($query) use ($bond) {
                $query->where('bond_id', $bond->id);
            })->count();

        if ($termo_document_count <= 0) {
            $attributes['impediment'] = true;
        }

        $bond->update($attributes);

        if ($bond->impediment === true) {
            $sec_UT = UserType::firstWhere('acronym', 'sec');
            $sec_users = User::where('active', true)->whereActiveUserType($sec_UT?->id)->get();

            $coord_UT = UserType::firstWhere('acronym', 'coord');
            $course_id = $bond->course?->id;
            $coord_users = User::where('active', true)->whereActiveUserType($coord_UT?->id)->whereUtaCourseId($course_id)->get();

            $users = $sec_users->merge($coord_users);

            Notification::send($users, new BondImpededNotification($bond));
        } else {
            $ldi_UT = UserType::firstWhere('acronym', 'ldi');
            $ldi_users = User::where('active', true)->whereActiveUserType($ldi_UT?->id)->get();

            Notification::send($ldi_users, new NewRightsNotification($bond));
        }

        return $bond;
    }

    /**
     * Undocumented function
     *
     * @param array<string, string> $attributes
     * @param Bond $bond
     *
     * @return Bond
     */
    public function requestReview(array $attributes, Bond $bond): Bond
    {
        $sec_UT = UserType::firstWhere('acronym', 'sec');
        $sec_users = User::where('active', true)->whereActiveUserType($sec_UT?->id)->get();

        $coord_UT = UserType::firstWhere('acronym', 'coord');
        $course_id = $bond->course?->id;
        $coord_users = User::where('active', true)->whereActiveUserType($coord_UT?->id)->whereUtaCourseId($course_id)->get();

        $ass_UT = UserType::firstWhere('acronym', 'ass');
        $ass_users = User::where('active', true)->whereActiveUserType($ass_UT?->id)->get();

        $users = $sec_users->merge($coord_users)->merge($ass_users);

        Notification::send($users, new RequestReviewNotification($bond));

        return $bond;
    }
}
