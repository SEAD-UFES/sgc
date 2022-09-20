<?php

namespace App\Http\Controllers;

use App\Http\Requests\Approved\CreateApprovedRequest;
use App\Http\Requests\Approved\ImportApprovedsFileRequest;
use App\Http\Requests\Approved\StoreApprovedBatchRequest;
use App\Services\ApprovedService;
use App\Services\ApprovedsSourceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;

class ApprovedBatchController extends Controller
{
    public function __construct(private ApprovedService $service, private ApprovedsSourceService $fileService)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateApprovedRequest $request
     *
     * @return View
     */
    public function createManyStep1(CreateApprovedRequest $request): View // import spreadsheet file view
    {
        return view('approved.createMany');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ImportApprovedsFileRequest  $request
     *
     * @return RedirectResponse
     */
    public function storeManyStep1(ImportApprovedsFileRequest $request): RedirectResponse
    {
        try {
            /**
             * @var UploadedFile $uploadeFile
             */
            $uploadeFile = $request->file('file');
            $importedApproveds = $this->fileService->importApprovedsFromFile($uploadeFile);

            session(['importedApproveds' => $importedApproveds]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->route('approveds.create_many.step_2')->with('success', 'Arquivo importado com sucesso.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  CreateApprovedRequest  $request
     *
     * @return View
     */
    public function createManyStep2(CreateApprovedRequest $request): View // import spreadsheet file view
    {
        $importedApproveds = session('importedApproveds');

        return view('approved.review', compact('importedApproveds'));
    }

    /**
     * Undocumented function
     *
     * @param StoreApprovedBatchRequest $request
     *
     * @return RedirectResponse
     */
    public function storeManyStep2(StoreApprovedBatchRequest $request): RedirectResponse
    {
        try {
            $this->service->batchStore($request->validated());
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o(s) aprovado(s): ' . $e->getMessage()]);
        }

        session()->forget('importedApproveds');

        return redirect()->route('approveds.index')->with('success', 'Aprovados importados com sucesso.');
    }
}
