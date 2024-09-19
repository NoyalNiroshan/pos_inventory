<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // List all roles
    public function index()
    {
        $roles = Role::all(); // Get all roles
        return view('role-permission.role.index', ['roles' => $roles]);    
    }

    // Show the form to create a new role
    public function create()
    {
        return view('role-permission.role.create');  
    }

    // Store the newly created role
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:roles,name' // Ensures the role name is unique in the 'roles' table
            ],
        ]);

        // Create a new role
        Role::create([
            'name' => $request->name
        ]);

        // Redirect back to the roles list with a success message
        return redirect('roles')->with('status', 'Role Created Successfully');
    }

    // Edit the role
    public function edit($id)
    {
        // Find the role by ID
        $role = Role::findOrFail($id);

        // Return the edit view with the role details
        return view('role-permission.role.edit', compact('role'));
    }

    // Update the role
    public function update(Request $request, Role $role)
    {
        // Validate the request
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('roles')->ignore($role->id),
            ],
        ]);

        // Update the role
        $role->update([
            'name' => $request->name,
        ]);

        // Redirect back with a success message
        return redirect('roles')->with('status', 'Role updated successfully!');
    }

    // Delete the role
    public function destroy(Role $role)
    {
        // Delete the role
        $role->delete();

        // Redirect back with a success message
        return redirect('roles')->with('status', 'Role deleted successfully!');
    }

    public function addPermissionToRole($roleId)
    {
        // Fetch all available permissions
        $permissions = Permission::get();
    
        // Find the role by its ID
        $role = Role::findOrFail($roleId); // Ensure that $role is properly fetched
          // Fetch the role's current permissions using DB facade
            $rolePermissions = DB::table('role_has_permissions')
            ->where('role_id', $role->id)
            ->pluck('permission_id') // Pluck only permission IDs
            ->all();  // Get all as an array

    
        // Return the view with the role and permissions
        return view('role-permission.role.add-permissions', [
            'role' => $role,         // Pass the role to the view
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }
    

 
public function givePermissionToRole(Request $request, $roleId)
{
    // Validate the incoming request
    $request->validate([
        'permission' => 'required' // Ensure permissions array is provided
        
    ]);

    // Find the role by ID
    $role = Role::findOrFail($roleId);

    // Sync the selected permissions with the role
    $role->syncPermissions($request->permission);

    // Redirect back with a success message
    return redirect()->back()->with('status', 'Permissions added to role successfully.');
}




}
