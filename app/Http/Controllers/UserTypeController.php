<?php

namespace App\Http\Controllers;

use App\Models\UserType;
use App\Services\UserTypeService;
use Illuminate\Http\Request;

class UserTypeController extends Controller
{
    private UserTypeService $service;

    public function __construct(UserTypeService $userTypeService)
    {
        $this->service = $userTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userTypes = $this->service->list();

        return view('userType.index', compact('userTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserType  $userType
     *
     * @return \Illuminate\Http\Response
     */
    public function show(UserType $userType)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserType  $userType
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(UserType $userType)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserType  $userType
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserType $userType)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserType  $userType
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserType $userType)
    {
    }
}
