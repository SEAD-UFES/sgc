<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RootController extends Controller
{
    public function rootFork()
    {
        if (Auth::check())
            return redirect()->route('home');
        else
            return redirect()->route('auth.form');
    }
}
