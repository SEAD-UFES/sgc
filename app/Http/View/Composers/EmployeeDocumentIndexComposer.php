<?php

namespace App\Http\View\Composers;

use App\Helpers\ModelFilterHelper;
use App\Models\EmployeeDocument;
use Illuminate\View\View;

class EmployeeDocumentIndexComposer
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
            'filters' => ModelFilterHelper::buildFilters(request(), EmployeeDocument::$accepted_filters),
        ]);
    }
}
