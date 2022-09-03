<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInstitutionalDetailRequest;
use App\Http\Requests\UpdateInstitutionalDetailRequest;
use App\Models\Bond;
use App\Models\Employee;
use App\Models\InstitutionalDetail;
use App\Models\Role;
use App\Models\User;
use App\Models\UserTypeAssignment;
use App\Services\InstitutionalDetailService;
use App\Services\MailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class InstitutionalDetailController extends Controller
{
    private InstitutionalDetailService $service;

    private MailService $mailService;

    public function __construct(InstitutionalDetailService $institutionalDetailService, MailService $mailService)
    {
        $this->service = $institutionalDetailService;
        $this->mailService = $mailService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(): void
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(Request $request): View
    {
        //check access permission
        if (! Gate::allows('employee-store')) {
            abort(403);
        }

        return view('institutionalDetail.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreInstitutionalDetailRequest  $request
     *
     * @return RedirectResponse
     */
    public function store(StoreInstitutionalDetailRequest $request, Employee $employee): RedirectResponse
    {
        //check access permission
        if (! Gate::allows('employee-store')) {
            abort(403);
        }

        try {
            $this->service->create($request->validated(), $employee);
        } catch (\Exception $e) {
            return redirect()->route('employees.show', $employee->id)->withErrors(['noStore' => 'Não foi possível salvar os Detalhes Intitucionais: ' . $e->getMessage()]);
        }

        return redirect()->route('employees.show', $employee->id)->with('success', 'Detalhes Institucionais criados com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Employee $employee
     *
     * @return View
     */
    public function edit(Request $request, Employee $employee): View
    {
        //check access permission
        if (! Gate::allows('employee-update')) {
            abort(403);
        }

        $institutionalDetail = $employee->institutionalDetail;

        return view('institutionalDetail.edit', compact('institutionalDetail', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateInstitutionalDetailRequest  $request
     * @param  Employee $employee
     *
     * @return RedirectResponse
     */
    public function update(UpdateInstitutionalDetailRequest $request, Employee $employee): RedirectResponse
    {
        //check access permission
        if (! Gate::allows('employee-update')) {
            abort(403);
        }

        /**
         * @var InstitutionalDetail $institutionalDetail
         */
        $institutionalDetail = $employee->institutionalDetail;

        try {
            $institutionalDetail = $this->service->update($request->validated(), $institutionalDetail, $employee);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar os Detalhes Institucionais: ' . $e->getMessage()]);
        }

        return redirect()->route('employees.show', $employee->id)->with('success', 'Detalhes Institucionais atualizados com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InstitutionalDetail  $institutionalDetail
     *
     * @return void
     */
    public function destroy(InstitutionalDetail $institutionalDetail): void
    {
    }

    /**
     * @param Request $request
     * @param Bond $bond
     *
     * @return RedirectResponse
     */
    public function sendNewEmployeeEmails(Bond $bond, Request $request): RedirectResponse
    {
        /**
         * @var UserTypeAssignment $loggedInUta
         */
        $loggedInUta = session('loggedInUser.currentUta');

        /**
         * @var User $loggedInUser
         */
        $loggedInUser = $loggedInUta->user;

        /**
         * @var Employee|null $loggedInEmployee
         */
        $loggedInEmployee = $loggedInUser->employee;

        /**
         * @var Role $employeeRole
         */
        $employeeRole = $bond->role;

        try {
            $this->mailService->sendInstitutionEmployeeLoginCreatedEmail($loggedInEmployee, $bond);
            $this->mailService->sendLmsAccessPermissionRequestEmail($loggedInEmployee, $bond);
            if (str_starts_with($employeeRole->name, 'Tutor')) {
                $this->mailService->sendNewTutorEmploymentNoticeEmail($loggedInEmployee, $bond);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['noSend' => 'Não foi possível enviar os emails: ' . $e->getMessage()]);
        }

        return redirect()->route('bonds.show', $bond->id)->with('success', 'E-mails enviados com sucesso.');
    }
}
