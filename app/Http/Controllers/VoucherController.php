<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AddAccount;
use App\Models\GrnAccount;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $currentDate = Carbon::today()->toDateString();

        $vouchers = Voucher::with(['voucherItems', 'user'])
            ->whereDate('created_at', $currentDate)
            ->get();
        return view('adminpages.voucher', ['userName' => $user->name,'userEmail' => $user->email],compact('vouchers'));
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

    return response()->json(['balance' => abs($balance)]);  
}




public function store(Request $request)
{
    $request->validate([
        'receiving_location' => 'required|string',
        'voucher_type' => 'required|string',
        'created_at' => 'nullable|date',
        'remarks' => 'nullable|string',
        'account.*' => 'required',
        'amount.*' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        $createdAt = $request->created_at ?? now();
        $updatedAt = $request->updated_at ?? $createdAt;

        $voucher = Voucher::create([
            'user_id' => Auth::id(),
            'receiving_location' => $request->receiving_location,
            'voucher_type' => $request->voucher_type,
            'cash_in_hand' => $request->input('cash_in_hand', 0),
            'totalAmount' => $request->input('totalAmount', 0),
            'remarks' => $request->remarks,
            'voucher_status' => 'complete',
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
        ]);

        $voucherId = $voucher->id; 

        $accounts = $request->input('account');
        $balances = $request->input('balance');
        $narrations = $request->input('narration');
        $amounts = $request->input('amount');

        $voucherItems = [];

        foreach ($accounts as $index => $accountId) {
            $voucherItems[] = VoucherItem::create([
                'voucher_id' => $voucherId,  
                'account' => $accountId,
                'balance' => $balances[$index] ?? 0,
                'narration' => $narrations[$index] ?? '',
                'amount' => $amounts[$index],
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
        }

        $cashInHandAccount = DB::table('add_accounts')
            ->where('sub_head_name', 'Cash In Hand')
            ->first();

        if ($cashInHandAccount) {
            $totalAmount = collect($voucherItems)->sum('amount');

            if ($request->voucher_type === 'Cash Payment') {
                DB::table('grn_accounts')->insert([
                    'voucher_id' => $voucherItems[0]->id,  
                    'vendor_account_id' => $cashInHandAccount->id,
                    'vendor_net_amount' => $totalAmount,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);

                foreach ($voucherItems as $voucherItem) {
                    DB::table('grn_accounts')->insert([
                        'voucher_id' => $voucherItem->id, 
                        'vendor_account_id' => $voucherItem->account,
                        'debit' => $voucherItem->amount,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                    ]);
                }

            } elseif ($request->voucher_type === 'Cash Receipt') {
                DB::table('grn_accounts')->insert([
                    'voucher_id' => $voucherItems[0]->id,  
                    'vendor_account_id' => $cashInHandAccount->id,
                    'debit' => $totalAmount,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ]);

                foreach ($voucherItems as $voucherItem) {
                    DB::table('grn_accounts')->insert([
                        'voucher_id' => $voucherItem->id, 
                        'vendor_account_id' => $voucherItem->account,
                        'vendor_net_amount' => $voucherItem->amount,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt,
                    ]);
                }
            }
        }

        DB::commit();

        return response()->json(['success' => true, 'message' => 'Voucher created successfully']);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Voucher creation failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Voucher creation failed.',
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}

public function destroy($id)
{
    try {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Voucher deleted successfully.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete voucher.',
            'error' => $e->getMessage()
        ], 500);
    }
}


}
