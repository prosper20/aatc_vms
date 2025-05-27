<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReceptionDashboardController extends Controller
{
    public function index()
    {
        return view('reception.dashboard');
    }
}
