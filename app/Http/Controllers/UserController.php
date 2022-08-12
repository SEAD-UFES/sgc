<?php

namespace App\Http\Controllers;

use App\Helpers\ModelFilterHelper;
use App\Helpers\SgcLogHelper;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateCurrentPassworRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\UserType;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    private UserService $service;

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
        if (! Gate::allows('user-list')) {
            abort(403);
        }

        //filters
        $filters = ModelFilterHelper::buildFilters($request, User::$accepted_filters);

        $users = $this->service->list();

        return view('user.index', compact('users', 'filters'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //check access permission
        if (! Gate::allows('user-store')) {
            abort(403);
        }

        $userTypes = UserType::orderBy('name')->get();

        return view('user.create', compact('userTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        //check access permission
        if (! Gate::allows('user-store')) {
            abort(403);
        }

        try {
            $this->service->create($request->validated());
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o usuário: ' . $e->getMessage()]);
        }

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Request $request)
    {
        //check access permission
        if (! Gate::allows('user-show')) {
            abort(403);
        }

        $this->service->read($user);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Request $request)
    {
        //check access permission
        if (! Gate::allows('user-update')) {
            abort(403);
        }

        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //check access permission
        if (! Gate::allows('user-update')) {
            abort(403);
        }

        try {
            $user = $this->service->update($request->validated(), $user);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o usuário: ' . $e->getMessage()]);
        }

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Request $request)
    {
        //check access permission
        if (! Gate::allows('user-destroy')) {
            abort(403);
        }

        try {
            $this->service->delete($user);
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir o usuário: ' . $e->getMessage()]);
        }

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso.');
    }

    public function setCurrentUTA(Request $request)
    {
        auth()->user()->setCurrentUta($request->activeUTAs);
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function currentPasswordEdit()
    {
        $user = auth()->user();
        return view('user.currentPasswordEdit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCurrentPassworRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function currentPasswordUpdate(UpdateCurrentPassworRequest $request)
    {
        $user = auth()->user();
        $request = $request->validated();
        $request['active'] = true;

        try {
            $user = $this->service->update($request, $user);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o usuário: ' . $e->getMessage()]);
        }

        return redirect()->route('home')->with('success', 'Usuário atualizado com sucesso.');
    }
}
