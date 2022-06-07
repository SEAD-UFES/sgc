<?php

namespace App\Http\Controllers;

use App\CustomClasses\SgcLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class WebController extends Controller
{
    public function rootFork()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return redirect()->route('auth.form');
    }

    public function home()
    {
        return view('home');
    }

    public function fallback()
    {
        return $this->rootFork();
    }

    public function showSysInfo(Request $request)
    {
        if (! Gate::allows('isAdm-global')) {
            SgcLogger::logBadAttemptOnUri($request->getRequestUri(), 403);
            abort(403);
        }
        return view('sysInfo');
    }
}
