<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\CustomClasses\SgcLogger;

class LoginController extends Controller
{
    public function getLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('login');
    }

    public function authenticate(LoginRequest $request)
    {
        $request->validated();

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'active' => true])) {
            $request->session()->regenerate();

            SgcLogger::writeLog();

            $firstUtaId = auth()->user()->getFirstUTA()?->id;
            auth()->user()->setCurrentUTA($firstUtaId);

            return redirect()->intended('home');
        }

        SgcLogger::writeLog(target: 'System', action: 'tried login', executor: $request);

        return back()->withErrors(['noAuth' => 'Não foi possível autenticar o usuário']);
    }

    public function logout(Request $request)
    {
        SgcLogger::writeLog();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('root');
    }
}
