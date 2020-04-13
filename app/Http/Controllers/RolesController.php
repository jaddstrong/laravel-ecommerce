<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    public function permission()
    {
        $permission = Permission::all();
        return response()->json($permission);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'role' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->role]);
        $permission = Permission::find($request->permission);
        $role->givePermissionTo($permission);
    }

    public function show(Request $request)
    {
        $role = Role::with('permissions')->find($request->id);
        return response()->json($role);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'role' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($request->id);
        $role->getAllPermissions();
        $role->revokePermissionTo($role->permissions);

        $permission = Permission::find($request->permission);
        $role->givePermissionTo($permission);

        $role->name = $request->role;
        $role->save();
    }

    public function delete(Request $request)
    {
        $role = Role::find($request->id);
        $role->delete();
    }
}
