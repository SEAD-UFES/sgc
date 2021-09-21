<?php

namespace App\Services;

use App\Models\Bond;
use App\Models\User;
use App\Models\UserType;
use App\Models\BondDocument;
use App\Models\DocumentType;
use App\CustomClasses\SgcLogger;
use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewBondNotification;
use App\Notifications\NewRightsNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BondImpededNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Notifications\RequestReviewNotification;

class BondService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        SgcLogger::writeLog(target: 'Bond', action: 'index');

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
     * @param array $attributes
     * @return Bond
     */
    public function create(array $attributes): Bond
    {
        $bond = new Bond;

        $bond->employee_id = $attributes['employees'];
        $bond->role_id = $attributes['roles'];
        $bond->course_id = $attributes['courses'];
        $bond->pole_id = $attributes['poles'];
        $bond->begin = $attributes['begin'];
        $bond->end = $attributes['end'];
        $bond->terminated_at = null;
        $bond->volunteer = isset($attributes['volunteer']);
        $bond->impediment = true;
        $bond->impediment_description = 'Vínculo ainda não revisado';
        $bond->uaba_checked_at = null;

        SgcLogger::writeLog(target: $bond, action: 'store');

        DB::transaction(function () use ($bond) {
            $bond->save();

            $documents = EmployeeDocument::where('employee_id', $bond->employee_id)->get();

            foreach ($documents as $doc) {
                $bondDocument = new BondDocument();
                $bondDocument->original_name = $doc->original_name;
                $bondDocument->file_data = $doc->file_data;
                $bondDocument->document_type_id = $doc->documentType->id;
                $bondDocument->bond_id = $bond->id;

                $bondDocument->save();
            }

            //Notify grantor assistants
            $ass_UT = UserType::firstWhere('acronym', 'ass');
            $coordOrAssistants = User::where('active', true)->whereActiveUserType($ass_UT->id)->get();
            Notification::send($coordOrAssistants, new NewBondNotification($bond));
        });

        return $bond;
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @param Bond $bond
     * @return Bond
     */
    public function update(array $attributes, Bond $bond): Bond
    {
        $bond->employee_id = $attributes['employees'];
        $bond->role_id = $attributes['roles'];
        $bond->course_id = $attributes['courses'];
        $bond->pole_id = $attributes['poles'];
        $bond->begin = $attributes['begin'];
        $bond->end = $attributes['end'];
        $bond->volunteer = isset($attributes['volunteer']);

        SgcLogger::writeLog(target: $bond, action: 'update');

        $bond->save();

        return $bond;
    }

    /**
     * Undocumented function
     *
     * @param Bond $bond
     * @return void
     */
    public function delete(Bond $bond)
    {
        SgcLogger::writeLog(target: $bond, action: 'destroy');

        $bond->delete();
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @param Bond $bond
     * @return Bond
     */
    public function review(array $attributes, Bond $bond): Bond
    {
        //get impediment; check if bond have 'termo'; if not, impediment = true.
        $bond->impediment = isset($attributes['impediment']);

        $termo_document_type_id = DocumentType::where('name', 'Ficha de Inscrição - Termos e Licença')->first()->id;
        $termo_document_count = BondDocument::where('document_type_id', $termo_document_type_id)->where('bond_id', $bond->id)->get()->count();
        if ($termo_document_count <= 0) $bond->impediment = true;

        $bond->impediment_description = $attributes['impedimentDescription'];
        $bond->uaba_checked_at = now();

        SgcLogger::writeLog(target: $bond, action: 'review');

        $bond->save();

        if ($bond->impediment == true) {
            $sec_UT = UserType::firstWhere('acronym', 'sec');
            $sec_users = User::where('active', true)->whereActiveUserType($sec_UT->id)->get();

            $coord_UT = UserType::firstWhere('acronym', 'coord');
            $course_id = $bond->course->id;
            $coord_users = User::where('active', true)->whereActiveUserType($coord_UT->id)->whereUtaCourseId($course_id)->get();

            $users = $sec_users->merge($coord_users);

            Notification::send($users, new BondImpededNotification($bond));
        } else {
            $ldi_UT = UserType::firstWhere('acronym', 'ldi');
            $ldi_users = User::where('active', true)->whereActiveUserType($ldi_UT->id)->get();

            Notification::send($ldi_users, new NewRightsNotification($bond));
        }

        return $bond;
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @param Bond $bond
     * @return Bond
     */
    public function requestReview(array $attributes, Bond $bond): Bond
    {
        $sec_UT = UserType::firstWhere('acronym', 'sec');
        $sec_users = User::where('active', true)->whereActiveUserType($sec_UT->id)->get();

        $coord_UT = UserType::firstWhere('acronym', 'coord');
        $course_id = $bond->course->id;
        $coord_users = User::where('active', true)->whereActiveUserType($coord_UT->id)->whereUtaCourseId($course_id)->get();

        $ass_UT = UserType::firstWhere('acronym', 'ass');
        $ass_users = User::where('active', true)->whereActiveUserType($ass_UT->id)->get();

        $users = $sec_users->merge($coord_users)->merge($ass_users);

        SgcLogger::writeLog(target: $bond, action: 'request review');

        Notification::send($users, new RequestReviewNotification($bond));

        return $bond;
    }
}
