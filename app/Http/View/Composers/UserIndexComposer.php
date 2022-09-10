<?php

namespace App\Http\View\Composers;

use App\Helpers\ModelFilterHelper;
use App\Models\User;
use Illuminate\View\View;

class UserIndexComposer
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
            'filters' => ModelFilterHelper::buildFilters(request(), User::$accepted_filters),
        ]);
    }
}
