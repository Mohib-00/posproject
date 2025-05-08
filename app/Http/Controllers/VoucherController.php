<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    public function addvoucher(){ 
        $user = Auth::user();      
        return view('adminpages.addvoucher', ['userName' => $user->name,'userEmail' => $user->email]);
    }
    public function voucher(){ 
        $user = Auth::user();      
        return view('adminpages.voucher', ['userName' => $user->name,'userEmail' => $user->email]);
    }
}
