<?php

namespace App\Http\View\Composers;

use App\Enums\CallStates;
use App\Helpers\ModelFilterHelper;
use App\Models\Applicant;
use Illuminate\View\View;

class ApplicantIndexComposer
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
            'filters' => ModelFilterHelper::buildFilters(request(), Applicant::$accepted_filters),
            'applicantStates' => CallStates::cases(),
        ]);
    }
}
