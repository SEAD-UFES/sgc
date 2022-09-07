<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bond\RequestBondReviewRequest;
use App\Models\Bond;
use App\Services\BondService;
use Illuminate\Http\RedirectResponse;

class RequestBondReview extends Controller
{
    public function __construct(private BondService $service)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RequestBondReviewRequest  $request
     * @param  Bond  $bond
     *
     * @return RedirectResponse
     */
    public function __invoke(RequestBondReviewRequest $request, Bond $bond): RedirectResponse
    {
        $bond = $this->service->requestReview($request->all(), $bond);

        return redirect()->route('bonds.show', $bond->id)->with('success', 'Revisão de vínculo solicitada.');
    }
}
