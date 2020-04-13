<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use Hash;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')->orderBy('id', 'DESC')->paginate(5);
        return view('users.index', compact('users'));
    }

    public function role()
    {
        $roles = Role::pluck('name', 'name')->all();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|same:confirmPassword'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $user = User::find($user->id);
        $user->assignRole($request->role);
    }

    public function show(Request $request)
    {
        $show = User::with('roles')->where('id', $request->id)->get();
        return $show;
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email'
        ]);

        $update = User::find($request->id);
        $update->name = $request->name;
        $update->email = $request->email;
        if($request->password != null)
        {
            if($request->password == $request->confirmPassword)
            {
                $update->password = Hash::make($request->password);
            }else{
                return "password not match";
            }
        }
        $update->save();

        $user = User::with('roles')->where('id', $request->id)->get();
        if(!empty($user->roles))
        {
            $update->removeRole($user[0]->roles[0]->name);
        }
        $update->assignRole($request->role);
    }

    public function delete(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
    }
}
