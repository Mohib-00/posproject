<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AddAccount;
use App\Models\GrnAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VoucherController extends Controller
{
    public function addvoucher(){ 
        $user = Auth::user();     
        $accounts = AddAccount::all();
        return view('adminpages.addvoucher', ['userName' => $user->name,'userEmail' => $user->email],compact('accounts'));
    }
    public function voucher(){ 
        $user = Auth::user();     
        return view('adminpages.voucher', ['userName' => $user->name,'userEmail' => $user->email]);
    }

    public function getCashInHand(Request $request)
{
    $voucherType = $request->input('voucher_type');
    
    $cashInHand = AddAccount::where('sub_head_name', 'Cash In Hand')->first();

    if (!$cashInHand) {
        return response()->json(['error' => 'Cash In Hand account not found.'], 404);
    }

    $cashInHandId = $cashInHand->id;

    $grnAccounts = GrnAccount::where('vendor_account_id', $cashInHandId)->get();

    $debitValues = $grnAccounts->pluck('debit')->toArray();
    $creditValues = $grnAccounts->pluck('vendor_net_amount')->toArray();

    $totalDebit = array_sum($debitValues);
    $totalCredit = array_sum($creditValues);

    if ($totalDebit > $totalCredit) {
        $cashBalance = $totalDebit - $totalCredit;
    } else {
        $cashBalance = $totalCredit - $totalDebit;
    }

    Log::info('Cash Balance: ' . $cashBalance);

    return response()->json(['cash_in_hand' => $cashBalance]);
}


public function getAccountBalance(Request $request)
{
    $accountId = $request->input('account_id');

    $grnAccounts = GrnAccount::where('vendor_account_id', $accountId)->get();

    if ($grnAccounts->isEmpty()) {
        return response()->json(['balance' => 0]);
    }

    $totalDebit = $grnAccounts->sum('debit');
    $totalCredit = $grnAccounts->sum('vendor_net_amount');

    $balance = $totalCredit - $totalDebit;

    return response()->json(['balance' => abs($balance)]);  // Show positive value
}

}
