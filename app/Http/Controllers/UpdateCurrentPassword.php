<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UpdateCurrentPasswordRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;

class UpdateCurrentPassword extends Controller
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
}
