<?php

namespace App\Http\View\Composers;

use App\Models\DocumentType;
use App\Models\Gender;
use App\Models\MaritalStatus;
use App\Models\State;
use Illuminate\View\View;

class EmployeeCreateComposer
{
    /**
     * Bind data to the view.
     * @param View $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with([
            'genders' => Gender::orderBy('name')->get(),
            'birthStates' => State::orderBy('name')->get(),
            'documentTypes' => DocumentType::orderBy('name')->get(),
            'maritalStatuses' => MaritalStatus::orderBy('name')->get(),
            'addressStates' => State::orderBy('name')->get(),
        ]);
    }
}
