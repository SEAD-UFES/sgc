<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function showSystem()
    {
        return view('system.index');
    }
}
