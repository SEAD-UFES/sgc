<?php

namespace App\Http\Controllers;

use App\Enums\Degrees;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $courseTypes = Degrees:: cases();

        return view('coursetype.index', ['courseTypes' => $courseTypes]);
    }
}
