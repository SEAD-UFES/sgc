<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\DestroyUserEmployeeLinkRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;

class DestroyUserEmployeeLinkController extends Controller
{
    public function __construct(private UserService $service)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param DestroyUserEmployeeLinkRequest $request
     * @param User $user
     *
     * @return RedirectResponse
     */
    public function __invoke(DestroyUserEmployeeLinkRequest $request, User $user): RedirectResponse
    {
        try {
            $this->service->unlinkEmployee($user);
        } catch (\Exception $exception) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o usuário: ' . $exception->getMessage()]);
        }

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }
}
