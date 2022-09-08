<?php

namespace App\Http\View\Composers;

use App\Models\Employee;
use Illuminate\View\View;

class UserFormComposer
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
            'employees' => Employee::orderBy('name')->get(),
        ]);
    }
}
