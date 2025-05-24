<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function create()
{
    return view('register-visitor-form');
}
}
