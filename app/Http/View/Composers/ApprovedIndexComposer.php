<?php

namespace App\Http\View\Composers;

use App\Helpers\ModelFilterHelper;
use App\Models\Approved;
use App\Models\ApprovedState;
use Illuminate\View\View;

class ApprovedIndexComposer
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
            'filters' => ModelFilterHelper::buildFilters(request(), Approved::$accepted_filters),
            'approvedStates' => ApprovedState::all(),
        ]);
    }
}
