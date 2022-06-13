<?php

namespace App\Http\Controllers;

use App\CustomClasses\SgcLogger;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class DocumentController extends Controller
{
    public function __construct(DocumentService $documentService)
    {
        $this->service = $documentService;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\BondDocument $bondDocument
     *
     * @return \Illuminate\Http\Response
     */
    public function showDocument($id, Request $request)
    {
        if (! (Gate::allows('employeeDocument-download') || Gate::allows('bondDocument-download') || Gate::allows('bondDocument-rights'))) {
            SgcLogger::logBadAttemptOnUri($request, 403);
            abort(403);
        }

        $file = $this->service->getDocument($id);

        //check access permission
        if (($file->class === 'App\Models\EmployeeDocument' && ! Gate::allows('employeeDocument-download')) ||
        ($file->class === 'App\Models\BondDocument' && ! $file->isRights && ! Gate::allows('bondDocument-download')) ||
        ($file->isRights && ! Gate::allows('bondDocument-rights'))
        ) {
            SgcLogger::logBadAttemptOnUri($request, 403);
            abort(403);
        }

        return Response::make($file->data, 200, ['filename="' . $file->name . '"'])->header('Content-Type', $file->mime);
    }
}
