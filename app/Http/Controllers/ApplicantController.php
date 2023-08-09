<?php

namespace App\Http\Controllers;

use App\Http\Requests\Applicant\CreateApplicantRequest;
use App\Http\Requests\Applicant\DestroyApplicantRequest;
use App\Http\Requests\Applicant\IndexApplicantRequest;
use App\Http\Requests\Applicant\StoreApplicantRequest;
use App\Http\Requests\Applicant\UpdateApplicantStateRequest;
use App\Models\Applicant;
use App\Services\ApplicantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ApplicantController extends Controller
{
    public function __construct(private ApplicantService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexApplicantRequest $request
     *
     * @return View
     */
    public function index(IndexApplicantRequest $request): View
    {
        $applicants = $this->service->list();

        foreach ($applicants as $applicant) {
            $applicant->landline = preg_replace(
                '~(\d{2})[^\d]{0,7}(\d{4})[^\d]{0,7}(\d{4})~',
                '($1) $2-$3',
                ($applicant->landline ?? '')
            );
            $applicant->mobile = preg_replace(
                '~(\d{2})[^\d]{0,7}(\d{5})[^\d]{0,7}(\d{4})~',
                '($1) $2-$3',
                ($applicant->mobile ?? '')
            ) ?? '';
        }

        return view('applicant.index', ['applicants' => $applicants])->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateApplicantRequest $request
     *
     * @return View
     */
    public function create(CreateApplicantRequest $request): View
    {
        return view('applicant.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreApplicantRequest $request
     *
     * @return RedirectResponse
     */
    public function store(StoreApplicantRequest $request)
    {
        //dd($request);
        try {
            $this->service->create($request->toDto());
        } catch (\Exception $exception) {
            return redirect()->route('applicants.index')->withErrors(['noStore' => 'Não foi possível salvar o Aprovado: ' . $exception->getMessage()]);
        }

        return redirect()->route('applicants.index')->with('success', 'Aprovado criado com sucesso.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateApplicantStateRequest  $request
     * @param  Applicant  $applicant
     *
     * @return RedirectResponse
     */
    public function update(UpdateApplicantStateRequest $request, Applicant $applicant): RedirectResponse
    {
        try {
            $this->service->changeState($request->all(), $applicant);
        } catch (\Exception $exception) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o Aprovado: ' . $exception->getMessage()]);
        }

        return redirect()->route('applicants.index')->with('success', 'Aprovado alterado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyApplicantRequest $request
     * @param  Applicant  $applicant
     *
     * @return RedirectResponse
     */
    public function destroy(DestroyApplicantRequest $request, Applicant $applicant): RedirectResponse
    {
        try {
            $this->service->delete($applicant);
        } catch (\Exception $exception) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir o Aprovado: ' . $exception->getMessage()]);
        }

        return redirect()->route('applicants.index')->with('success', 'Aprovado retirado da lista.');
    }
}
