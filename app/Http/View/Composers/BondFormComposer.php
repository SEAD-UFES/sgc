<?php

namespace App\Http\View\Composers;

use App\Enums\KnowledgeAreas;
use App\Models\Course;
use App\Models\Employee;
use App\Models\Pole;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class BondFormComposer
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
            'courses' => $this->getAllowedCourses(),
            'employees' => Employee::orderBy('name')->get(),
            'knowledgeAreas' => KnowledgeAreas::getValuesInAlphabeticalOrder(),
            'poles' => Pole::orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    /** @return Collection<int, Course>  */
    private static function getAllowedCourses(): Collection
    {
        $courses = Course::orderBy('name')->get();
        foreach ($courses as $key => $course) {
            if (! Gate::allows('bond-store-course_id', $course->id)) {
                $courses->forget($key);
            }
        }

        return $courses;
    }
}
