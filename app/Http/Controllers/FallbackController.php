<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FallbackController extends Controller
{
    public function fallback()
    {
        if (Auth::check())
            return redirect()->route('home');
        else
            return redirect()->route('login');
    }
}
