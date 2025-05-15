<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockReportContoller extends Controller
{
    public function stockreport()
{
    $user = Auth::user();
    $currentDate = now()->toDateString();
    
    $purchases = Purchase::where('stock_status', 'complete')
        ->whereDate('created_at', $currentDate)
        ->get();

    $selectedProductIds = [];

    foreach ($purchases as $purchase) {
        $productIds = json_decode($purchase->products ?? '[]');
        $selectedProductIds = array_merge($selectedProductIds, $productIds);
    }

    $products = Product::whereIn('id', $selectedProductIds)->get();

    $purchasesvendors = Purchase::where('stock_status', 'complete')
     
        ->get();

    return view('adminpages.stockreport', [
        'userName' => $user->name,
        'userEmail' => $user->email,
        'purchases' => $purchases,
        'selectedProductIds' => $selectedProductIds,
        'products' => $products,
        'purchasesvendors' => $purchasesvendors
    ]);
}

public function search(Request $request)
{
    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');
    $vendor = $request->input('vendors');

    $query = Purchase::query()->where('stock_status', 'complete');

    if ($fromDate && $toDate) {
        $fromDate = Carbon::parse($fromDate)->startOfDay();
        $toDate = Carbon::parse($toDate)->endOfDay();
        $query->whereBetween('created_at', [$fromDate, $toDate]);
    } elseif ($fromDate) {
        $fromDate = Carbon::parse($fromDate)->startOfDay();
        $query->whereDate('created_at', $fromDate);
    } elseif ($toDate) {
        $toDate = Carbon::parse($toDate)->endOfDay();
        $query->whereDate('created_at', $toDate);
    }

    if ($vendor) {
        $query->where('vendors', $vendor);
    }

    $purchases = $query->get();

    $selectedProductIds = [];
    foreach ($purchases as $purchase) {
        $productIds = json_decode($purchase->products ?? '[]');
        $selectedProductIds = array_merge($selectedProductIds, $productIds);
    }

    $products = Product::whereIn('id', $selectedProductIds)->get();
    $purchasesvendors = Purchase::where('stock_status', 'complete')->get();
    $user = Auth::user();

    return view('adminpages.stockreport', [
        'purchases' => $purchases,
        'selectedProductIds' => $selectedProductIds,
        'products' => $products,
        'purchasesvendors' => $purchasesvendors,
        'userName' => $user->name,
        'userEmail' => $user->email,
    ]);
}



}
