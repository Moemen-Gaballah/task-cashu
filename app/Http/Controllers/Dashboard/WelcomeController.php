<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sales;
use Auth;

class WelcomeController extends Controller
{
    public function index(){
    	
        if(Auth::user()->role == 1){
    	    $users_count = User::count();
            $sales_count = Sales::count();
        }else {
            $user_id = Auth::user()->user_id == 0 ? Auth::user()->id : Auth::user()->user_id;
            $users_count = User::where('user_id', $user_id)->count();
            $sales_count = Sales::where('owner', $user_id)->count();
        }
      
        return view('dashboard.welcome', compact('users_count', 'sales_count'));
    } //end of index
} //end of controller
