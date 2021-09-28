<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\CustomClasses\SgcLogger;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\CustomClasses\ModelFilterHelpers;

class UserController extends Controller
{
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check access permission
        if (!Gate::allows('user-list')) return response()->view('access.denied')->setStatusCode(401);

        //filters
        $filters = ModelFilterHelpers::buildFilters($request, User::$accepted_filters);
        
        $users = $this->service->list();

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
        if (!Gate::allows('user-store')) return response()->view('access.denied')->setStatusCode(401);

        $userTypes = UserType::orderBy('name')->get();

        SgcLogger::writeLog(target: 'User');

        return view('user.create', compact('userTypes'));
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
        if (!Gate::allows('user-store')) return response()->view('access.denied')->setStatusCode(401);

        try {
            $this->service->create($request->all());
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o usuário: ' . $e->getMessage()]);
        }

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
        if (!Gate::allows('user-show')) return response()->view('access.denied')->setStatusCode(401);

        SgcLogger::writeLog(target: $user);

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
        if (!Gate::allows('user-update')) return response()->view('access.denied')->setStatusCode(401);

        SgcLogger::writeLog(target: $user);

        return view('user.edit', compact('user'));
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
        if (!Gate::allows('user-update')) return response()->view('access.denied')->setStatusCode(401);

        try {
            $user = $this->service->update($request->all(), $user);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o usuário: ' . $e->getMessage()]);
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
        //check access permission
        if (!Gate::allows('user-destroy')) return response()->view('access.denied')->setStatusCode(401);

        try {
            $this->service->delete($user);
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
