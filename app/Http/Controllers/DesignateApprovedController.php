<?php

namespace App\Http\Controllers;

use App\Events\EmployeeDesignated;
use App\Http\Requests\Approved\DesignateApprovedRequest;
use App\Models\Approved;
use App\Services\ApprovedService;
use Exception;
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
        try {
            $employee = $this->service->designateApproved($approved);
        } catch (Exception $e) {
            return back()->withErrors(['noStore' => 'Não é possível nomear o aprovado: ' . $e->getMessage()]);
        }

        $fromApproved = true;

        EmployeeDesignated::dispatch($employee);

        return view('approved.designate', compact(['employee', 'fromApproved']));
    }
}
