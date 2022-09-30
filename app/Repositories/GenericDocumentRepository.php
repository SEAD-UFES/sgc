<?php

namespace App\Repositories;

use App\Models\Document;
use Illuminate\Support\Facades\DB;

class GenericDocumentRepository
{
    /**
     * @param int $id
     *
     * @return Document
     */
    public static function getById(int $id)
    {
        return Document::where('id', $id)->with('documentable')->firstOrFail();
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public static function delete(int $id)
    {
        /** @var bool $flag */
        $flag = false;

        DB::transaction(static function () use ($id, &$flag) {
            $document = self::getById($id);
            $document->documentable->delete();
            $flag = $document->delete();
        });

        return $flag ?? false;
    }
}
