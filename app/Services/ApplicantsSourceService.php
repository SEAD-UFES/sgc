<?php

namespace App\Services;

use App\Events\FileImported;
use App\Imports\ApplicantsImport;
use App\Models\Applicant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ApplicantsSourceService
{
    /**
     * Undocumented function
     *
     * @param UploadedFile $file
     *
     * @return Collection<int, Applicant>
     */
    public function readSourceSpreadsheet(UploadedFile $file): Collection
    {
        /**
         * @var Collection<int, Applicant> $applicants
         */
        $applicants = $this->importApplicantsFromSpreadsheet($file);

        $realPath = $file->getRealPath();

        Storage::delete($realPath);

        FileImported::dispatch($file->getClientOriginalName());

        return $applicants;
    }

    /**
     * Undocumented function
     *
     * @param UploadedFile $uploadedFile
     *
     * @return Collection<int, Applicant>
     */
    public function importApplicantsFromSpreadsheet(UploadedFile $uploadedFile): Collection
    {
        /**
         * @var Collection<int, Applicant> $applicants
         */
        $applicants = new Collection();
        Excel::import(new ApplicantsImport($applicants), $uploadedFile);

        return $applicants;
    }
}
