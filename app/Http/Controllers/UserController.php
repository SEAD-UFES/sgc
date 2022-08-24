<?php

namespace App\Http\Controllers;

use App\Helpers\ModelFilterHelper;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateCurrentPassworRequest;
use App\Http\Requests\UpdateUserEmployeeLinkRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserType;
use App\Services\UserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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
     * @param Request $request
     *
     * @return View
     */
    public function index(Request $request): View
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
     * @param Request $request
     *
     * @return View
     */
    public function create(Request $request): View
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
     * @param  StoreUserRequest  $request
     *
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request): RedirectResponse
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
     * @param  User  $user
     *
     * @return View
     */
    public function show(User $user, Request $request): View
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
     * @param User $user
     * @param Request $request
     *
     * @return View
     */
    public function edit(User $user, Request $request): View
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
     * @param  UpdateUserRequest  $request
     * @param  User  $user
     *
     * @return RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
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
     * @param User  $user
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function destroy(User $user, Request $request): RedirectResponse
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

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function setCurrentUTA(Request $request): RedirectResponse
    {
        /**
         * @var User $currentUser
         */
        $currentUser = auth()->user();

        /**
         * @var int $newActiveUtaId
         */
        $newActiveUtaId = $request->activeUTAs;

        $currentUser->setCurrentUta($newActiveUtaId);
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return View
     */
    public function currentPasswordEdit(): View
    {
        $user = auth()->user();
        return view('user.currentPasswordEdit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCurrentPassworRequest $request
     *
     * @return RedirectResponse
     */
    public function currentPasswordUpdate(UpdateCurrentPassworRequest $request): RedirectResponse
    {
        $user = auth()->user();

        /**
         * @var array<string, string> $requestArr
         */
        $requestArr = $request->validated();
        $requestArr['active'] = true;

        try {
            $user = $this->service->update($requestArr, $user);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o usuário: ' . $e->getMessage()]);
        }

        return redirect()->route('home')->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * @param UpdateUserEmployeeLinkRequest $request
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function updateEmployeeLink(UpdateUserEmployeeLinkRequest $request, User $user): RedirectResponse
    {
        if (! Gate::allows('user-update')) {
            abort(403);
        }

        try {
            $this->service->linkEmployee($request->validated(), $user);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o usuário: ' . $e->getMessage()]);
        }

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * @param Request $request
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function destroyEmployeeLink(Request $request, User $user): RedirectResponse
    {
        if (! Gate::allows('user-update')) {
            abort(403);
        }

        try {
            $this->service->unlinkEmployee($user);
        } catch (\Exception $e) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o usuário: ' . $e->getMessage()]);
        }

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * @param User $user
     *
     * @return View
     */
    public function editEmployeeLink(User $user): View
    {
        if (! Gate::allows('user-update')) {
            abort(403);
        }

        $employees = Employee::orderBy('name')->get();

        return view('user.editEmployeeLink', compact('user', 'employees'));
    }
}
