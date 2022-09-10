<?php

namespace App\Http\Controllers;

use App\Helpers\ModelFilterHelper;
use App\Http\Requests\RightsDocument\IndexRightsDocumentRequest;
use App\Http\Requests\RightsDocument\ShowRightsDocumentRequest;
use App\Models\BondDocument;
use App\Services\BondDocumentService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\View\View;

class RightsDocumentController extends Controller
{
    public function __construct(private BondDocumentService $service)
    {
    }

    /**
     * @param IndexRightsDocumentRequest $request
     *
     * @return View
     */
    public function index(IndexRightsDocumentRequest $request): View
    {
        /**
         * @var string $sort
         */
        $sort = $request->query('sort') ?? '';

        /**
         * @var string $direction
         */
        $direction = $request->query('direction') ?? '';

        $filters = ModelFilterHelper::buildFilters($request, BondDocument::$accepted_filters);
        $documents = $this->service->listRights(sort: $sort, direction: $direction);

        return view('reports.rightsIndex', compact('documents', 'filters'));
    }

    /**
     * Display the specified resource.
     *
     * @param ShowRightsDocumentRequest $request
     * @param  int $id
     *
     * @return Response
     */
    public function show(ShowRightsDocumentRequest $request, int $id): Response
    {
        $file = $this->service->getDocument($id);

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

        return FacadesResponse::make($data, 200, ['filename=' => $fileName])->header('Content-Type', $mime);
    }
}
