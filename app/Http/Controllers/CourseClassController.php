<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseClass\CreateCourseClassRequest;
use App\Http\Requests\CourseClass\DestroyCourseClassRequest;
use App\Http\Requests\CourseClass\EditCourseClassRequest;
use App\Http\Requests\CourseClass\IndexCourseClassRequest;
use App\Http\Requests\CourseClass\ShowCourseClassRequest;
use App\Http\Requests\CourseClass\StoreCourseClassRequest;
use App\Http\Requests\CourseClass\UpdateCourseClassRequest;
use App\Models\CourseClass;
use App\Services\CourseClassService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseClassController extends Controller
{
    public function __construct(private CourseClassService $service)
    {
    }

    /**
     * @param IndexCourseClassRequest $request
     * @return View
     */
    public function index(IndexCourseClassRequest $request): View
    {
        $courseClasses = $this->service->list();

        return view('courseClass.index', compact('courseClasses'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create(CreateCourseClassRequest $request): View
    {
        return view('courseClass.create');
    }

    public function store(StoreCourseClassRequest $request): RedirectResponse
    {
        try {
            $this->service->create($request->toDto());
        } catch (\Exception $e) {
            return redirect()->route('course-classes.index')->withErrors(['noStore' => 'Não foi possível salvar a Disciplina: ' . $e->getMessage()]);
        }

        return redirect()->route('course-classes.index')->with('success', 'Disciplina criada com sucesso.');
    }

    public function show(ShowCourseClassRequest $request, CourseClass $courseClass): View
    {
        $courseClass = $this->service->read($courseClass);
        return view('courseClass.show', compact('courseClass'));
    }

    public function edit(EditCourseClassRequest $request, CourseClass $courseClass): View
    {
        $courseClass = $this->service->read($courseClass);
        return view('courseClass.edit', compact('courseClass'));
    }

    public function update(UpdateCourseClassRequest $request, CourseClass $courseClass): RedirectResponse
    {
        try {
            $this->service->update($request->toDto(), $courseClass);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar a Disciplina: ' . $e->getMessage()]);
        }

        return redirect()->route('course-classes.index')->with('success', 'Disciplina atualizada com sucesso.');
    }

    public function destroy(DestroyCourseClassRequest $request, CourseClass $courseClass): RedirectResponse
    {
        try {
            $this->service->delete($courseClass);
        } catch (\Exception $e) {
            return redirect()->route('course-classes.index')->withErrors(['noDestroy' => 'Não foi possível excluir a Disciplina: ' . $e->getMessage()]);
        }

        return redirect()->route('course-classes.index')->with('success', 'Disciplina excluída com sucesso.');
    }
}
