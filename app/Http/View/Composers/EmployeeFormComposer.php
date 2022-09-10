<?php

namespace App\Http\View\Composers;

use App\Enums\Genders;
use App\Enums\MaritalStatuses;
use App\Models\DocumentType;
use App\Models\State;
use Illuminate\View\View;

class EmployeeFormComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with([
            'genders' => Genders::getValuesInAlphabeticalOrder(),
            'birthStates' => State::orderBy('name')->get(),
            'documentTypes' => DocumentType::orderBy('name')->get(),
            'maritalStatuses' => MaritalStatuses::getValuesInAlphabeticalOrder(),
            'addressStates' => State::orderBy('name')->get(),
        ]);
    }
}
