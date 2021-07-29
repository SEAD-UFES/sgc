<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\CustomClasses\SgcLogger;
use App\Models\UserType;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //star query build
        $users_query = User::sortable(['created_at' => 'desc'])->with(['userType', 'employee'])->orderBy('email');

        //building filters (to build interface)
        $filters = [
            'email' => $request->input('email') ? $request->input('email') : null,
            'employee' => $request->input('employee') ? $request->input('employee') : null,
            'user_type' => $request->input('user_type') ? $request->input('user_type') : null,
            'active' => $request->input('active') ? $request->input('active') : null,
        ];

        //apply filters
        if ($filters['email']) {
            $users_query = $users_query->where('users.email', 'like', '%' . $filters['email'] . '%');
        }
        if ($filters['employee']) {
            $users_query = $users_query->wherehas('employee', function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['employee'] . '%');
            });
        }
        if ($filters['user_type']) {
            $users_query = $users_query->wherehas('userType', function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['user_type'] . '%');
            });
        }
        if ($filters['active']) {
            $is_active = null;
            if (in_array(strtolower($filters['active']), ['sim', '1', 'true'])) $is_active = 1;
            if (in_array(strtolower($filters['active']), ['não', 'nao', '0', 'false']))  $is_active = 0;
            $users_query = $users_query->where('users.active', '=', $is_active);
        }

        //get paginate
        $users = $users_query->paginate(10);

        //add query string on page links
        $users->appends($request->all());

        //write on log;
        SgcLogger::writeLog('User');

        return view('user.index', compact('users', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $userTypes = UserType::orderBy('name')->get();
        $user = new User;

        SgcLogger::writeLog('User');

        return view('user.create', compact('userTypes', 'user'));
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
        $user->user_type_id = $request->userTypes;
        $user->active = $request->has('active');

        $existentEmployee = Employee::where('email', $request->email)->first();

        if (!is_null($existentEmployee)) {
            $user->employee_id = $existentEmployee->id;

            SgcLogger::writeLog($existentEmployee, 'Updated existent Employee info on User');
        }

        try {
            $user->save();
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o usuário: ' . $e->getMessage()]);
        }

        SgcLogger::writeLog($user);

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso.');
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
        $userTypes = UserType::orderBy('name')->get();

        SgcLogger::writeLog($user);

        return view('user.edit', compact('user', 'userTypes'));
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
        $user->user_type_id = $request->userTypes;
        $user->active = $request->has('active');

        $existentEmployee = Employee::where('email', $request->email)->first();

        if (!is_null($existentEmployee)) {
            $user->employee_id = $existentEmployee->id;

            SgcLogger::writeLog($existentEmployee, 'Updated existent Employee info on User');
        }

        try {
            $user->save();
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o usuário: ' . $e->getMessage()]);
        }

        SgcLogger::writeLog($user);

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

    public function setCurrentBond(Request $request)
    {
        session('sessionUser')->setCurrentBond($request->activeBonds);

        return redirect()->route('home');
    }
}
