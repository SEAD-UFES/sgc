<?php

namespace App\Http\View\Composers;

use App\Enums\KnowledgeAreas;
use App\Models\Course;
use App\Models\CourseClass;
use App\Models\Employee;
use App\Models\Pole;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
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
        $knowledgeAreas = Arr::sort(KnowledgeAreas::cases(), static function ($knowledgeArea) {
            return $knowledgeArea->label();
        });

        $allowedCourses = $this->getAllowedCourses();

        $allowedCoursesIds = $allowedCourses->pluck('id')->toArray();

        $view->with([
            'courses' => $allowedCourses,
            'courseClasses' => CourseClass::orderBy('name')->whereIn('course_id', $allowedCoursesIds)->get(),
            'employees' => Employee::orderBy('name')->get(),
            'knowledgeAreas' => $knowledgeAreas,
            'poles' => Pole::orderBy('name')->get(),
            'roles' => Role::orderBy('name')->get(),
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
