<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\CustomClasses\SgcLogger;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('role')->orderBy('email')->paginate(10);

        SgcLogger::writeLog('User');

        return view('user.index', compact('users'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $user = new User;

        SgcLogger::writeLog('User');

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

        SgcLogger::writeLog($user);

        $existentEmployeeId = Employee::where('email', $request->email)->pluck('id')->first();

        if ($existentEmployeeId != null) {
            Employee::where('id', $existentEmployeeId)->update(['user_id' => $user->id]);

            SgcLogger::writeLog($existentEmployeeId, 'updated employee');
        }

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso.');;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();

        SgcLogger::writeLog($user);

        return view('user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->email = $request->email;

        if ($request->password != '')
            $user->password =  Hash::make($request->password);
        $user->role_id = $request->roles;
        $user->active = $request->has('active');

        try {
            $user->save();
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o usuário: ' . $e->getMessage()]);
        }

        SgcLogger::writeLog($user);

        $existentEmployeeId = Employee::where('email', $request->email)->pluck('id')->first();

        if ($existentEmployeeId != null) {
            Employee::where('id', $existentEmployeeId)->update(['user_id' => $user->id]);

            SgcLogger::writeLog($existentEmployeeId, 'updated employee');
        }

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        SgcLogger::writeLog($user);

        try {
            $user->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir o usuário: ' . $e->getMessage()]);
        }

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso.');
    }
}
