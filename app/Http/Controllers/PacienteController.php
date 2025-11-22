<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class PacienteController extends Controller
{
    public function index()
    {
        return view('paciente.dashboard');
    }
}