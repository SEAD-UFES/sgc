<?php

namespace App\Http\Controllers;

use App\Http\Requests\Applicant\CreateApplicantRequest;
use App\Http\Requests\Applicant\ImportApplicantsFileRequest;
use App\Http\Requests\Applicant\StoreApplicantBatchRequest;
use App\Services\ApplicantService;
use App\Services\ApplicantsSourceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;

class ApplicantBatchController extends Controller
{
    public function __construct(private ApplicantService $service, private ApplicantsSourceService $fileService)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateApplicantRequest $request
     *
     * @return View
     */
    public function createManyStep1(CreateApplicantRequest $request): View // import spreadsheet file view
    {
        return view('applicant.createMany');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ImportApplicantsFileRequest  $request
     *
     * @return RedirectResponse
     */
    public function storeManyStep1(ImportApplicantsFileRequest $request): RedirectResponse
    {
        try {
            /**
             * @var UploadedFile $uploadeFile
             */
            $uploadeFile = $request->file('file');
            $importedApplicants = $this->fileService->importApplicantsFromFile($uploadeFile);

            session(['importedApplicants' => $importedApplicants]);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->route('applicants.create_many.step_2')->with('success', 'Arquivo importado com sucesso.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  CreateApplicantRequest  $request
     *
     * @return View
     */
    public function createManyStep2(CreateApplicantRequest $request): View // import spreadsheet file view
    {
        $importedApplicants = session('importedApplicants');

        return view('applicant.review', compact('importedApplicants'));
    }

    /**
     * Undocumented function
     *
     * @param StoreApplicantBatchRequest $request
     *
     * @return RedirectResponse
     */
    public function storeManyStep2(StoreApplicantBatchRequest $request): RedirectResponse
    {
        try {
            $this->service->batchStore($request->validated());
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o(s) aprovado(s): ' . $e->getMessage()]);
        }

        session()->forget('importedApplicants');

        return redirect()->route('applicants.index')->with('success', 'Aprovados importados com sucesso.');
    }
}
