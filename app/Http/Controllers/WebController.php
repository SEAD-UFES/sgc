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

    public function fallback() { return $this->rootFork(); }
}
