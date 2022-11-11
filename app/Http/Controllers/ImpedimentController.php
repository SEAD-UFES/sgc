<?php

namespace App\Http\Controllers;

use App\Models\Impediment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ImpedimentController extends Controller
{
    // public function __construct(private ImpedimentService $service)
    // {
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $bondId = $request->input('bond_id');
        try {
            Impediment::create([
                'bond_id' => $bondId,
                'description' => $request->impediment_description,
                'reviewer_id' => $request->user()->id,
                'reviewed_at' => now(),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível criar o impedimento: ' . $e->getMessage()]);
        }

        return redirect()->route('bonds.show', $bondId)->with('success', 'Impedimento criado com sucesso.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Impediment $impediment
     *
     * @return RedirectResponse
     */
    public function update(Request $request, Impediment $impediment): RedirectResponse
    {
        try {
            $closedById = $request->user()->id;
            $closedAt = now();

            $impediment->update([
                'closed_by_id' => $closedById,
                'closed_at' => $closedAt,
            ]);

            $bondId = $impediment->bond_id;
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível atualizar o impedimento: ' . $e->getMessage()]);
        }

        return redirect()->route('bonds.show', $bondId)->with('success', 'Impedimento atualizado com sucesso.');
    }
}
