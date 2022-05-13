<?php

namespace App\Services;

use App\Imports\ApprovedsImport;
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
     * @return Collection
     */
    public function importApprovedsFromFile(UploadedFile $file): Collection
    {

        $filePath = $this->getFilePath($file);

        $approveds = $this->getApprovedsFromFile($filePath);
        Storage::delete($filePath);

        return $approveds;
    }

    /**
     * Undocumented function
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function getFilePath(UploadedFile $file): string
    {
        $fileName = $file->getClientOriginalName();

        return $file->storeAs('temp', $fileName, 'local');
    }

    /**
     * Undocumented function
     *
     * @param string $filePath
     * @return Collection
     */
    protected function getApprovedsFromFile(string $filePath): Collection
    {
        $approveds = collect();
        Excel::import(new ApprovedsImport($approveds), $filePath);

        return $approveds;
    }
}
