<?php

namespace App\Http\Controllers;

use App\Services\UserTypeService;
use Illuminate\View\View;

class UserTypeController extends Controller
{
    public function __construct(private UserTypeService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $userTypes = $this->service->list();

        return view('userType.index', compact('userTypes'));
    }
}
