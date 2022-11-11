<?php

namespace App\Http\Controllers;

use App\Http\Requests\Document\CreateDocumentRequest;
use App\Http\Requests\Document\IndexDocumentRequest;
use App\Http\Requests\Document\ShowDocumentRequest;
use App\Http\Requests\Document\StoreDocumentRequest;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function __construct(private DocumentService $service)
    {
    }

    /**
     * @param IndexDocumentRequest $request
     *
     * @return View
     */
    public function index(IndexDocumentRequest $request): View
    {
        /**
         * @var string $sort
         */
        $sort = $request->query('sort') ?? '';

        /**
         * @var string $direction
         */
        $direction = $request->query('direction') ?? '';

        $documents = $this->service->list(sort: $sort, direction: $direction);

        return view('document.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateDocumentRequest $request
     *
     * @return View
     */
    public function create(CreateDocumentRequest $request): View
    {
        return view('document.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDocumentRequest $request
     *
     * @return RedirectResponse
     */
    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $this->service->create($request->toDto());

        return redirect()->route('documents.index')->with('success', 'Arquivo importado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param ShowDocumentRequest $request
     * @param  int $id
     *
     * @return Response
     */
    public function show(ShowDocumentRequest $request, int $id): Response
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
