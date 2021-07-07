<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FallbackController extends Controller
{
    public function fallback()
    {
        return redirect()->route('root');
    }
}
