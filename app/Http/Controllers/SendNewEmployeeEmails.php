<?php

namespace App\Http\Controllers;

use App\Events\InstitutionalLoginConfirmed;
use App\Models\Bond;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SendNewEmployeeEmails extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Bond $bond
     *
     * @return RedirectResponse
     */
    public function __invoke(Request $request, Bond $bond): RedirectResponse
    {
        InstitutionalLoginConfirmed::dispatch($bond);

        return redirect()->route('bonds.show', $bond->id)->with('success', 'E-mails enviados para a fila de envio.');
    }
}
