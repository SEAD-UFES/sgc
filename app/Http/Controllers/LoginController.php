<?php

namespace App\Http\Controllers;

use App\Helpers\SgcLogHelper;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class LoginController extends Controller
{
    /**
     * @return RedirectResponse|View
     */
    public function getLoginForm(): RedirectResponse|View
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('login');
    }

    /**
     * @param LoginRequest $request
     *
     * @return RedirectResponse
     *
     * @throws UnauthorizedHttpException
     */
    public function authenticate(LoginRequest $request)
    {
        $request->validated();

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'active' => true])) {
            $request->session()->regenerate();

            SgcLogHelper::writeLog();

            /**
             * @var User  $authUser
             */
            $authUser = auth()->user();

            $firstUtaId = $authUser->getFirstUta()?->id;
            $authUser->setCurrentUta($firstUtaId);

            return redirect()->route('home');
        }

        throw new UnauthorizedHttpException('Unauthorized');
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function logout(Request $request)
    {
        SgcLogHelper::writeLog();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.form');
    }
}
