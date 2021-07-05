<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Controllers\Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $roles = Role::all();

        return view('user.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $user = new User;
        return view('user.create', compact('roles', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $user = new User;

        $user->email = $request->email;
        $user->password =  Hash::make($request->password);
        $user->role_id = $request->roles;
        $user->active = $request->has('active');

        $user->save();

        $existentEmployeeId = Employee::where('email', $request->email)->pluck('id')->first();

        if ($existentEmployeeId != null)
            Employee::where('id', $existentEmployeeId)->update(['user_id' => $user->id]);

        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid, User $user)
    {
        $user = User::findOrFail($uuid);
        $roles = Role::all();
        return view('user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update($uuid, Request $request, User $user)
    {
        $user = User::findOrFail($uuid);

        /* echo (isset($request->password));
        dd($request); */

        $user->email = $request->email;
        if ($request->password != '')
            $user->password =  Hash::make($request->password);
        $user->role_id = $request->roles;
        $user->active = $request->has('active');

        try {
            $user->save();
        } catch (\Exception $e) {
            return back()->withErrors(['noAuth' => 'Não foi possível autenticar o usuário: ' . $e->getMessage()]);
        }

        $existentEmployeeId = Employee::where('email', $request->email)->pluck('id')->first();

        if ($existentEmployeeId != null)
            Employee::where('id', $existentEmployeeId)->update(['user_id' => $user->id]);

        return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
