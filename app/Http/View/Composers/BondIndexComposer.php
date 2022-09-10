<?php

namespace App\Http\View\Composers;

use App\Helpers\ModelFilterHelper;
use App\Models\Bond;
use Illuminate\View\View;

class BondIndexComposer
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
            'filters' => ModelFilterHelper::buildFilters(request(), Bond::$accepted_filters),
        ]);
    }
}
