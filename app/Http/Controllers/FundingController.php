<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FundingController extends Controller
{
    public function showFunding()
    {
        return view('funding.index');
    }
}
