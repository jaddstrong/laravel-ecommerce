<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\User;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        // $request->session()->forget('cart');
        // $request->session()->forget('user');
        // $request->session()->forget('key');
        // $request->session()->push('cart', 'user');
        // $request->session()->push('cart', ['item','pcs']);
        // $request->session()->push('cart', ['item','pcs']);
        
        // $request->session()->push('cart', 'user1');
        // $request->session()->push('cart.user1', ['item','pcs']);
        // $request->session()->push('cart.user1', ['item','pcs']);
        // $request->session()->put('cart');
        $data = $request->session()->get('cart');
        dd($data);
        return view("users");
    }
}
