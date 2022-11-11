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
    public function importApplicantsFromFile(UploadedFile $file): Collection
    {
        /**
         * @var string $filePath
         */
        $filePath = $this->getFilePath($file);

        /**
         * @var Collection<int, Applicant> $applicants
         */
        $applicants = $this->getApplicantsFromFile($filePath);

        Storage::delete($filePath);

        FileImported::dispatch($filePath);

        return $applicants;
    }

    /**
     * Undocumented function
     *
     * @param UploadedFile $file
     *
     * @return string|false
     */
    protected function getFilePath(UploadedFile $file): string|false
    {
        /**
         * @var string $fileName
         */
        $fileName = $file->getClientOriginalName();

        return $file->storeAs('temp', $fileName, 'local');
    }

    /**
     * Undocumented function
     *
     * @param string $filePath
     *
     * @return Collection<int, Applicant>
     */
    protected function getApplicantsFromFile(string $filePath): Collection
    {
        /**
         * @var Collection<int, Applicant> $applicants
         */
        $applicants = new Collection();
        Excel::import(new ApplicantsImport($applicants), $filePath);

        return $applicants;
    }
}
