<?php

namespace App\Http\Controllers;

use App\Enums\Genders;
use App\Enums\MaritalStatuses;
use App\Events\EmployeeDesignated;
use App\Http\Requests\Approved\DesignateApprovedRequest;
use App\Models\Approved;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\State;
use App\Services\ApprovedService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DesignateApprovedController extends Controller
{
    public function __construct(private ApprovedService $service)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param DesignateApprovedRequest $request
     * @param Approved $approved
     *
     * @return RedirectResponse|View
     */
    public function __invoke(DesignateApprovedRequest $request, Approved $approved): RedirectResponse|View
    {
        $existantEmployee = Employee::where('email', $approved->email)->first();
        if ($existantEmployee) {
            return redirect()->route('approveds.index')->withErrors(['employeeAlreadyExists' => 'Já existe Colaborador no sistema com o mesmo email do Aprovado.']);
        }

        $genders = Genders::getValuesInAlphabeticalOrder();
        $birthStates = State::orderBy('name')->get();
        $documentTypes = DocumentType::orderBy('name')->get();
        $maritalStatuses = MaritalStatuses::getValuesInAlphabeticalOrder();
        $addressStates = State::orderBy('name')->get();

        // Create a temporary object Employee to fill with the approved current data
        $employee = new Employee(
            [
                'name' => $approved->name,
                'email' => $approved->email,
                'area_code' => $approved->area_code,
                'phone' => $approved->phone,
                'mobile' => $approved->mobile,
            ]
        );

        $fromApproved = true;

        EmployeeDesignated::dispatch($employee);

        return view('approved.designate', compact('genders', 'birthStates', 'documentTypes', 'maritalStatuses', 'addressStates', 'employee', 'fromApproved'));
    }
}
