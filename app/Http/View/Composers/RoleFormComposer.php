<?php

namespace App\Http\View\Composers;

use App\Models\GrantType;
use Illuminate\View\View;

class RoleFormComposer
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
            'grantTypes' => GrantType::orderBy('name')->get(),
        ]);
    }
}
