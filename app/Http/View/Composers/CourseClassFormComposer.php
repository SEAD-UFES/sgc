<?php

namespace App\Http\View\Composers;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CourseClassFormComposer
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
        ]);
    }

    /** @return Collection<int, Course>  */
    private function getAllowedCourses(): Collection
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
