<?php

namespace App\Http\Controllers;

use view;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
      $users=User::get();
        return view('role-permission.user.index',
        [
            'users'=>$users
        ] );    
    }

    public function create()
    {
        $roles=Role::pluck('name')->all();
        return view('role-permission.user.create',[
            'roles'=>$roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:20',
            'roles'=>'required'
        ]);

        $user=User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
           
        ]);

$user->syncRoles($request->roles);

        return redirect('users')->with('status', 'User created successfully');
    }

    public function edit(User $user)
    {

        $roles=Role::pluck('name')->all();
        $selectedRoles=$user->roles->pluck('name','name')->all();
        return view('role-permission.user.edit',[
            'roles'=>$roles,
            'user'=>$user,
            'selectedRoles'=>$selectedRoles
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|                       ',
            'roles'=>'required'
        ]);


        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect('users')->with('status', 'User updated successfully');
    }
    
}
