<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class RequestHelper
{
    public static function getFileDataFromRequest($request, $field)
    {
        //if file
        if ($request->file()) {
            $file_name = time() . '.' . $request->file->getClientOriginalName();
            $file_path = $request->file($field)->storeAs('temp', $file_name, 'local');
            $file_content = file_get_contents(base_path('storage/app/' . $file_path), true);
            $file_content_base64 = base64_encode($file_content);
            Storage::delete($file_path);
            return $file_content_base64;
        }

        //if no file
        return null;
    }

    public static function getFileDataFromFilePath($file_path)
    {
        //if file_path
        if ($file_path) {
            $file_content = file_get_contents(base_path('storage/app/' . $file_path), true);
            $file_content_base64 = base64_encode($file_content);
            return $file_content_base64;
        }

        //if no file
        return null;
    }
}
