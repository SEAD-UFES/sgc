<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class WebController extends Controller
{
    /**
     * Control what happens when attempting to access "/"
     *
     * @return RedirectResponse
     */
    public function rootFork(): RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return redirect()->route('auth.form');
    }

    /**
     * Control what happens when attempting to access "/home"
     *
     * @return View
     */
    public function home(): View
    {
        return view('home');
    }

    // *** Don't use the fallback route. Let the user know that the page doesn't exist and log the error.
    // public function fallback()
    // {
    //     return $this->rootFork();
    // }

    /**
     * @return View
     */
    public function showSysInfo(Request $request): View
    {
        if (! Gate::allows('isAdm-global')) {
            abort(403);
        }

        return view('sysInfo');
    }
}
