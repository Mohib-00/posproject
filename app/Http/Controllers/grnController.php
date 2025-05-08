<?php

namespace App\Http\Controllers;

use App\Models\AddAccount;
use App\Models\GrnAccount;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class grnController extends Controller
{
    public function openGRN(){ 
        $user = Auth::user();      
        $purchases = Purchase::where('stock_status', 'pending')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('adminpages.grn', ['userName' => $user->name,'userEmail' => $user->email],compact('purchases'));
    }

    public function getPurchaseDetails($id)
    {
        $purchase = Purchase::find($id);
    
        if (!$purchase) {
            return response()->json(['error' => 'Purchase not found'], 404);
        }
    
        $productIds = json_decode($purchase->products, true);
    
        $productNames = Product::whereIn('id', $productIds)->pluck('item_name', 'id');
    
        $productNamesArray = $productNames->toArray();
    
        return response()->json([
            'id' => $purchase->id,
            'vendors' => $purchase->vendors,
            'invoice_no' => $purchase->invoice_no,
            'receiving_location' => $purchase->receiving_location,
            'created_at' => $purchase->created_at,
            'remarks' => $purchase->remarks,
            'totalquantity' => $purchase->totalquantity,
            'gross_amount' => $purchase->gross_amount,
            'discount' => $purchase->discount,
            'net_amount' => $purchase->net_amount,
            'products' => $purchase->products,
            'quantity' => $purchase->quantity,
            'retail_rate' => $purchase->retail_rate,
            'purchase_rate' => $purchase->purchase_rate,
            'single_retail_rate' => $purchase->single_retail_rate,
            'single_purchase_rate' => $purchase->single_purchase_rate,
            'product_names' => $productNamesArray,
        ]);
    }

   

    public function updatePurchaseStock(Request $request)
    {
        $purchaseId = $request->input('purchase_id');
        $productIds = $request->input('products');
        $quantities = $request->input('quantity');
        $purchaseRates = $request->input('purchase_rate');
        $retailRates = $request->input('retail_rate');
        $UPRs = $request->input('single_purchase_rate');
        $URRs = $request->input('single_retail_rate');
        $netAmount = $request->input('net_amount', 0);
        $discount = $request->input('discount', 0);
        $grossAmount = $request->input('gross_amount', 0);
    
        $purchase = Purchase::find($purchaseId);
        if (!$purchase) {
            return response()->json(['message' => 'Purchase not found.'], 404);
        }
    
        $purchase->stock_status = 'complete';
        $purchase->save();
    
        foreach ($productIds as $index => $productId) {
            $product = Product::find($productId);
            if ($product) {
                $product->quantity = $quantities[$index];
                $product->purchase_rate = $purchaseRates[$index];
                $product->retail_rate = $retailRates[$index];
                $product->single_purchase_rate = $UPRs[$index];
                $product->single_retail_rate = $URRs[$index];
                $product->save();
            }
        }
    
        $vendorAccount = AddAccount::where('sub_head_name', $purchase->vendors)->first();
    
        if (!$vendorAccount) {
            return response()->json(['message' => 'Vendor not found in add_accounts.'], 404);
        }
    
        GrnAccount::create([
            'vendor_account_id' => $vendorAccount->id,
            'vendor_net_amount' => $netAmount,
            'purchase_id' => $purchase->id,
            'grn' => 'grn',
        ]);
    
        if ($discount > 0) {
            $discountAccount = AddAccount::where('sub_head_name', 'Discount Availed')->first();
    
            if (!$discountAccount) {
                return response()->json(['message' => 'Discount Availed account not found.'], 404);
            }
    
            GrnAccount::create([
                'vendor_account_id' => $discountAccount->id,
                'discount' => $discount,
                'purchase_id' => $purchase->id,
                'grn' => 'grn',
            ]);
        }
    
        $inventoryAccount = AddAccount::where('sub_head_name', 'Inventory')->first();
    
        if ($inventoryAccount) {
            GrnAccount::create([
                'vendor_account_id' => $inventoryAccount->id,
                'debit' => $grossAmount,
                'purchase_id' => $purchase->id,
                'grn' => 'grn',
            ]);
        }
    
        return response()->json(['message' => 'Purchase, products, and GRN account updated successfully.']);
    }
    
    
    
    

    

}
