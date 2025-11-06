<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class NutricionistaController extends Controller
{
    public function index()
    {
        return view('nutricionista.dashboard');
    }
}
