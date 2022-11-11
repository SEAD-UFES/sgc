<?php

namespace App\Http\View\Composers;

use App\Enums\Degrees;
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
            'degrees' => Degrees::cases(),
        ]);
    }
}
