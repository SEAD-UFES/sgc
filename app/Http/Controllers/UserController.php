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
use App\CustomClasses\ModelFilterHelpers;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check access permission
        if (!Gate::allows('user-list')) return view('access.denied');

        $users_query = User::with(['userType', 'employee']);

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, User::$accepted_filters);
        $users_query = $users_query->AcceptRequest(User::$accepted_filters)->filter();

        //sort
        $users_query = $users_query->sortable(['updated_at' => 'desc']);

        //get paginate and add querystring on paginate links
        $users = $users_query->paginate(10);
        $users->appends($request->all());

        //write on log
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
        //check access permission
        if (!Gate::allows('user-store')) return view('access.denied');

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
        //check access permission
        if (!Gate::allows('user-store')) return view('access.denied');

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
        //check access permission
        if (!Gate::allows('user-show')) return view('access.denied');

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
        //check access permission
        if (!Gate::allows('user-update')) return view('access.denied');

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
        //check access permission
        if (!Gate::allows('user-update')) return view('access.denied');

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
        //check access permission
        if (!Gate::allows('user-destroy')) return view('access.denied');

        SgcLogger::writeLog($user);

        try {
            $user->delete();
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir o usuário: ' . $e->getMessage()]);
        }

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso.');
    }

    public function setCurrentUTA(Request $request)
    {
        session('sessionUser')->setCurrentUTA($request->activeUTAs);
        return redirect()->back();
    }
}
