<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class RequestHelper
{
    public static function getFileDataFromRequest($request, $field)
    {
        //if file
        if ($request->file()) {
            $fileName = time() . '.' . $request->file->getClientOriginalName();
            $filePath = $request->file($field)->storeAs('temp', $fileName, 'local');
            $fileContent = file_get_contents(base_path('storage/app/' . $filePath), true);
            $fileContentBase64 = base64_encode($fileContent);
            Storage::delete($filePath);
            return $fileContentBase64;
        }

        //if no file
        return null;
    }

    public static function getFileDataFromFilePath($filePath)
    {
        //if filePath
        if ($filePath) {
            $fileContent = file_get_contents(base_path('storage/app/' . $filePath), true);
            $fileContentBase64 = base64_encode($fileContent);
            return $fileContentBase64;
        }

        //if no file
        return null;
    }
}
