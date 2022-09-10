<?php

namespace App\Http\View\Composers;

use App\Models\Course;
use App\Models\User;
use App\Models\UserType;
use Illuminate\View\View;

class ResponsibilityFormComposer
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
            'users' => User::orderBy('email')->get(),
            'userTypes' => UserType::orderBy('name')->get(),
        ]);
    }
}
