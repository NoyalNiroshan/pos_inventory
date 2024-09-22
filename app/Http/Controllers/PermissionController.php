<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
 public function index(){
    $permisions=Permission::get();
    return view('role-permission.permission.index',
['permisions'=>$permisions]);    
 }

 public function create(){
   return view('role-permission.permission.create');  
 }

 public function edit($id){
    // Find the permission by ID
    $permission = Permission::findOrFail($id);

    // Return the edit view with the permission details
    return view('role-permission.permission.edit', compact('permission'));
 }



 public function store(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'name' => [
            'required',
            'string',
            'unique:permissions,name' // Ensures the permission name is unique in the 'permissions' table
        ],
    ]);

    // Create a new permission
    Permission::create([
        'name' => $request->name
    ]);

    // Redirect back to the permissions list with a success message
    return redirect('permissions')->with('status', 'Permission Created Successfully');
}

public function update(Request $request, Permission $permission)
{
    // Validate the request
    $request->validate([
        'name' => [
            'required',
            'string',
            Rule::unique('permissions')->ignore($permission->id),
        ],
    ]);

    // Update the permission
    $permission->update([
        'name' => $request->name,
    ]);

    // Redirect back with a success message
    return redirect('permissions')->with('status', 'Permission updated successfully!');
}


public function destroy(Permission $permission)
{
    // Delete the permission
    $permission->delete();

    // Redirect back with a success message
    return redirect('permissions')->with('status', 'Permission deleted successfully!');
}




}
