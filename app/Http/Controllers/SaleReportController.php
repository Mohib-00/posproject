<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleReportController extends Controller
{
    public function salereport(){ 
        $user = Auth::user();      
        $currentDate = Carbon::today();
        $sales = Sale::with(['user', 'saleItems'])
                 ->whereDate('created_at', $currentDate)
                 ->get();
        return view('adminpages.salereport', ['userName' => $user->name,'userEmail' => $user->email],compact('sales'));
    }



    public function searchSales(Request $request)
{
    $user = Auth::user();

    $fromDate = $request->input('from_date');
    $toDate = $request->input('to_date');

    $sales = Sale::with(['user', 'saleItems'])
                 ->when($fromDate, function ($query, $fromDate) {
                     return $query->whereDate('created_at', '>=', $fromDate);
                 })
                 ->when($toDate, function ($query, $toDate) {
                     return $query->whereDate('created_at', '<=', $toDate);
                 })
                 ->get();

    return view('adminpages.salereport',['userName' => $user->name,'userEmail' => $user->email], compact('sales'));
}
}
