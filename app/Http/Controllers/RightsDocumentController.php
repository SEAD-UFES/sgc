<?php

namespace App\Http\Controllers;

use App\Http\Requests\RightsDocument\IndexRightsDocumentRequest;
use App\Http\Requests\RightsDocument\ShowRightsDocumentRequest;
use App\Services\RightsDocumentService;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class RightsDocumentController extends Controller
{
    public function __construct(private RightsDocumentService $service)
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
        $sort = $request->query('sort');

        /**
         * @var string $direction
         */
        $direction = $request->query('direction');

        $documents = $this->service->list(direction: $direction, sort: $sort);

        return view('reports.rightsIndex', compact('documents'));
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
        /**
         * @var Collection<string, string> $file
         */
        $file = $this->service->assembleDocument($id);

        return response($file->get('data'), 200, [
            'Content-Type' => $file->get('mime'),
            'Content-Disposition' => 'inline',
        ]);
    }
}
