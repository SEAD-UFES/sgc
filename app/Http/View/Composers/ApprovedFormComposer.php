<?php

namespace App\Http\View\Composers;

use App\Models\Course;
use App\Models\Pole;
use App\Models\Role;
use Illuminate\View\View;

class ApprovedFormComposer
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
            'courses' => Course::orderBy('name')->get(),
            'poles' => Pole::orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }
}
