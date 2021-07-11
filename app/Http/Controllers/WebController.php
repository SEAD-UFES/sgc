<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    public function rootFork()
    {
        if (Auth::check())
            return redirect()->route('home');
        else
            return redirect()->route('auth.form');
    }

    public function webHome() { return view('web.home'); }

    public function webEmployee() { return view('web.employee'); }

    public function webFunding() { return view('web.funding'); }

    public function webReport() { return view('web.report'); }

    public function webSystem() { return view('web.system'); }

    public function fallback() { return $this->rootFork(); }
}
