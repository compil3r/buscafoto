<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard principal
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Exibe a página inicial (landing page)
     */
    public function landing()
    {
        return view('landing');
    }
}
