<?php

namespace App\Services;

use App\Imports\ApprovedsImport;
use App\Models\Approved;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ApprovedsSourceService
{
    /**
     * Undocumented function
     *
     * @param UploadedFile $file
     *
     * @return Collection<Approved>
     */
    public function importApprovedsFromFile(UploadedFile $file): Collection
    {
        /**
         * @var string $filePath
         */
        $filePath = $this->getFilePath($file);

        /**
         * @var Collection<Approved> $approveds
         */
        $approveds = $this->getApprovedsFromFile($filePath);

        Storage::delete($filePath);

        return $approveds;
    }

    /**
     * Undocumented function
     *
     * @param UploadedFile $file
     *
     * @return string
     */
    protected function getFilePath(UploadedFile $file): string
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
     * @return Collection<Approved>
     */
    protected function getApprovedsFromFile(string $filePath): Collection
    {
        /**
         * @var Collection<Approved> $approveds
         */
        $approveds = collect();
        Excel::import(new ApprovedsImport($approveds), $filePath);

        return $approveds;
    }
}
