<?php

namespace App\Http\Controllers;

use App\Events\EmployeeDesignated;
use App\Http\Requests\Applicant\DesignateApplicantRequest;
use App\Models\Applicant;
use App\Services\ApplicantService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DesignateApplicantController extends Controller
{
    public function __construct(private ApplicantService $service)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param DesignateApplicantRequest $request
     * @param Applicant $applicant
     *
     * @return RedirectResponse|View
     */
    public function __invoke(DesignateApplicantRequest $request, Applicant $applicant): RedirectResponse|View
    {
        try {
            $employee = $this->service->designateApplicant($applicant);
        } catch (Exception $exception) {
            return back()->withErrors(['noStore' => 'Não é possível nomear o aprovado: ' . $exception->getMessage()]);
        }

        $fromApplicant = true;

        EmployeeDesignated::dispatch($employee);

        return view('applicant.designate', ['employee' => $employee, 'fromApplicant' => $fromApplicant]);
    }
}
