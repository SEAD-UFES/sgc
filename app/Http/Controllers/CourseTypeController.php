<?php

namespace App\Http\Controllers;

use App\Services\CourseTypeService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseTypeController extends Controller
{
    public function __construct(private CourseTypeService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $courseTypes = $this->service->list();

        return view('coursetype.index', compact('courseTypes'));
    }
}
