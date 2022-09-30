<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeDocument\CreateEmployeeDocumentRequest;
use App\Http\Requests\EmployeeDocument\IndexEmployeeDocumentRequest;
use App\Http\Requests\EmployeeDocument\ShowEmployeeDocumentRequest;
use App\Http\Requests\EmployeeDocument\StoreEmployeeDocumentRequest;
use App\Services\EmployeeDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class EmployeeDocumentController extends Controller
{
    public function __construct(private EmployeeDocumentService $service)
    {
    }

    /**
     * @param IndexEmployeeDocumentRequest $request
     *
     * @return View
     */
    public function index(IndexEmployeeDocumentRequest $request): View
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

        return view('employee.document.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateEmployeeDocumentRequest $request
     *
     * @return View
     */
    public function create(CreateEmployeeDocumentRequest $request): View
    {
        return view('employee.document.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEmployeeDocumentRequest $request
     *
     * @return RedirectResponse
     */
    public function store(StoreEmployeeDocumentRequest $request): RedirectResponse
    {
        $this->service->create($request->toDto());

        return redirect()->route('employees_documents.index')->with('success', 'Arquivo importado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param ShowEmployeeDocumentRequest $request
     * @param  int $id
     *
     * @return Response
     */
    public function show(ShowEmployeeDocumentRequest $request, int $id): Response
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
