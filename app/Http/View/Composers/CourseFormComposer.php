<?php

namespace App\Http\View\Composers;

use App\Models\CourseType;
use Illuminate\View\View;

class CourseFormComposer
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
            'courseTypes' => CourseType::orderBy('name')->get(),
        ]);
    }
}
