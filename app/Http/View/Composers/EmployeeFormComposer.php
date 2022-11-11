<?php

namespace App\Http\View\Composers;

use App\Enums\Genders;
use App\Enums\MaritalStatuses;
use App\Enums\States;
use App\Models\DocumentType;
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
            'genders' => Genders::cases(),
            'states' => States::cases(),
            'documentTypes' => DocumentType::whereIn('name', ['RG', 'CNH', 'Passaporte', 'Carteira de Identidade Profissional'])->orderBy('name')->get(),
            'maritalStatuses' => MaritalStatuses::cases(),
        ]);
    }
}
