<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class WebController extends Controller
{
    public function rootFork()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        } else {
            return redirect()->route('auth.form');
        }
    }

    public function webHome()
    {
        return view('home');
    }

    public function fallback()
    {
        return $this->rootFork();
    }

    public function showSysInfo()
    {
        if (!Gate::allows('isAdm-global')) {
            return response()->view('access.denied')->setStatusCode(403);
        }
        return view('sysInfo');
    }
}
