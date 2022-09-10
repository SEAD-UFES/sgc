<?php

namespace App\Services;

use App\Events\BondCreated;
use App\Events\BondImpeded;
use App\Events\BondLiberated;
use App\Events\BondReviewRequested;
use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Events\RightsDocumentArchived;
use App\Helpers\TextHelper;
use App\Models\Bond;
use App\Models\BondDocument;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\EmployeeDocument;
use App\Services\Dto\ReviewBondDto;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class BondService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Bond::class);

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

                /**
                 * @var Document $employeeBaseDocument
                 */
                $employeeBaseDocument = $employeeDocument->document;

                /**
                 * @var DocumentType $employeeBaseDocumentType
                 */
                $employeeBaseDocumentType = $employeeBaseDocument->documentType;

                $newDocument = new Document();
                $newDocument->original_name = $employeeBaseDocument->original_name;
                $newDocument->file_data = $employeeBaseDocument->file_data;
                $newDocument->document_type_id = $employeeBaseDocumentType->id;
                $newDocument->documentable_type = BondDocument::class;
                $newDocument->documentable_id = $bondDocument->id;
                $newDocument->save();
            }

            BondCreated::dispatch($bond);
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
        ModelRead::dispatch($bond);

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
     * @param ReviewBondDto $reviewBondDto
     * @param Bond $bond
     *
     * @return Bond
     */
    public function review(ReviewBondDto $reviewBondDto, Bond $bond): Bond
    {
        //get impediment; check if bond have 'rights'; if not, impediment = true.
        $impediment = $reviewBondDto->impediment;
        $impediment_description = $reviewBondDto->impedimentDescription;

        $bondHaveRights = $bond->hasRightsDocuments();

        if (!$bondHaveRights) {
            $impediment = true;
            $impediment_description = "{$impediment_description}\n[SGC: O Sistema não encontrou documento de Termos e Licença.]";
        }

        $bond->update([
            'impediment' => $impediment,
            'impediment_description' => $impediment_description,
            'uaba_checked_at' => now(),
        ]);

        if ($bond->impediment === true) {
            BondImpeded::dispatch($bond);
        } else {
            BondLiberated::dispatch($bond);
            RightsDocumentArchived::dispatch($bond);
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
        BondReviewRequested::dispatch($bond);

        return $bond;
    }
}
