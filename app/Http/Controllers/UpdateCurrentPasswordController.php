<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UpdateCurrentPasswordRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;

class UpdateCurrentPasswordController extends Controller
{
    public function __construct(private UserService $service)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param UpdateCurrentPasswordRequest $request
     *
     * @return RedirectResponse
     */
    public function __invoke(UpdateCurrentPasswordRequest $request): RedirectResponse
    {
        /**
         * @var User $user
         */
        $user = auth()->user();

        try {
            $user = $this->service->updateCurrentPassword($request->toDto(), $user);
        } catch (\Exception $exception) {
            return back()->withErrors(['noStore' => 'Não foi possível salvar o usuário: ' . $exception->getMessage()]);
        }

        return redirect()->route('home')->with('success', 'Usuário atualizado com sucesso.');
    }
}
