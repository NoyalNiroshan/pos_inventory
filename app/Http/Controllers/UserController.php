<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
      
        return view('role-permission.user.index', ['roles' => $roles]);    
    }
    
}
