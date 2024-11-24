<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Role, User};

class FrontController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $users = User::with('role')->get();

        return view('front.index', compact('roles', 'users'));
    }
}
