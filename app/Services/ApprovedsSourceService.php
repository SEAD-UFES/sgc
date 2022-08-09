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
     * @return Collection<int, Approved>
     */
    public function importApprovedsFromFile(UploadedFile $file): Collection
    {
        /**
         * @var string $filePath
         */
        $filePath = $this->getFilePath($file);

        /**
         * @var Collection<int, Approved> $approveds
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
     * @return Collection<int, Approved>
     */
    protected function getApprovedsFromFile(string $filePath): Collection
    {
        /**
         * @var Collection<int, Approved> $approveds
         */
        $approveds = collect();
        Excel::import(new ApprovedsImport($approveds), $filePath);

        return $approveds;
    }
}
