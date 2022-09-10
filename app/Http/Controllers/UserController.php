<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\DestroyUserRequest;
use App\Http\Requests\User\EditUserRequest;
use App\Http\Requests\User\IndexUserRequest;
use App\Http\Requests\User\ShowUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private UserService $service)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexUserRequest $request
     *
     * @return View
     */
    public function index(IndexUserRequest $request): View
    {
        $users = $this->service->list();

        return view('user.index', compact('users'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateUserRequest $request
     *
     * @return View
     */
    public function create(CreateUserRequest $request): View
    {
        return view('user.create');
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
     * @param ShowUserRequest $request
     * @param  User  $user
     *
     * @return View
     */
    public function show(ShowUserRequest $request, User $user): View
    {
        $this->service->read($user);

        return view('user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @param EditUserRequest $request
     *
     * @return View
     */
    public function edit(EditUserRequest $request, User $user): View
    {
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
     * @param DestroyUserRequest $request
     *
     * @return RedirectResponse
     */
    public function destroy(DestroyUserRequest $request, User $user): RedirectResponse
    {
        try {
            $this->service->delete($user);
        } catch (\Exception $e) {
            return back()->withErrors(['noDestroy' => 'Não foi possível excluir o usuário: ' . $e->getMessage()]);
        }

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso.');
    }
}
