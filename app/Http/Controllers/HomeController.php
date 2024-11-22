<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'ppm') {
            return view('ppm.dashboard');
        } else if (Auth::user()->role == 'auditor') {
            return view('auditor.dashboard');
        } else if (Auth::user()->role == 'auditee') {
            return view('auditee.dashboard');
        }
    }
}
