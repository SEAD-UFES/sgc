<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeDocument\CreateEmployeeDocumentRequest;
use App\Http\Requests\EmployeeDocument\IndexEmployeeDocumentRequest;
use App\Http\Requests\EmployeeDocument\ShowEmployeeDocumentRequest;
use App\Http\Requests\EmployeeDocument\StoreEmployeeDocumentRequest;
use App\Services\EmployeeDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as FacadesResponse;
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
        $documents = $this->service->list(sort: $request->query('sort'), direction: $request->query('direction'));

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

        return redirect()->route('employeesDocuments.index')->with('success', 'Arquivo importado com sucesso.');
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
