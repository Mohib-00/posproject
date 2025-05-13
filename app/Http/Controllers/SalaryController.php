<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalaryController extends Controller
{
     public function salarys(){ 
        $user = Auth::user();      
        return view('adminpages.salary', ['userName' => $user->name,'userEmail' => $user->email]);
    }
}
