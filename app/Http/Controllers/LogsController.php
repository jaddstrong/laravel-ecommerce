<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Log;
use Auth;

class LogsController extends Controller
{
    public function purchaseList()
    {
        $user = Auth::user()->id;
        $logs = Log::where('user_id', '=',$user)->get();
        
        return response()->json($logs);
    }

    public function logs()
    {
        $user = Auth::user()->id;
        $logs = Log::where('user_id', $user)->get();
        
        return view('mails.index')->with($logs);
    }
}
