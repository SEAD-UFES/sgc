<?php

namespace App\Http\View\Composers;

use App\Helpers\ModelFilterHelper;
use App\Models\BondDocument;
use Illuminate\View\View;

class BondDocumentIndexComposer
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
            'filters' => ModelFilterHelper::buildFilters(request(), BondDocument::$accepted_filters),
        ]);
    }
}
