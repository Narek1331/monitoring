<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CodeController extends Controller
{
    public function index()
    {
        return view('pages.code');
    }
}
