<?php

namespace App\Http\Controllers;

use App\Models\BondDocument;
use App\Models\EmployeeDocument;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class DocumentController extends Controller
{
    protected DocumentService $service;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->service = new DocumentService(null, null);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param Request $request
     *
     * @return HttpResponse
     */
    public function showDocument(int $id, Request $request): HttpResponse
    {
        if (! (Gate::allows('employeeDocument-download') || Gate::allows('bondDocument-download') || Gate::allows('bondDocument-rights'))) {
            abort(403);
        }

        $file = $this->service->getDocument($id);

        //check access permission
        if (($file->get('class') === EmployeeDocument::class && ! Gate::allows('employeeDocument-download')) ||
        ($file->get('class') === BondDocument::class && ! $file->get('isRights') && ! Gate::allows('bondDocument-download')) ||
        ($file->get('isRights') && ! Gate::allows('bondDocument-rights'))
        ) {
            abort(403);
        }

        /**
         * @var string $data
         */
        $data = $file->get('data');

        /**
         * @var string $fileName
         */
        $fileName = $file->get('name');

        /**
         * @var string $mime
         */
        $mime = $file->get('mime');

        return Response::make($data, 200, ['filename=' => $fileName])->header('Content-Type', $mime);
    }
}
