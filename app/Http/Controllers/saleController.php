<?php

namespace App\Http\Controllers;

use App\Models\AddAccount;
use App\Models\customer;
use App\Models\GrnAccount;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class saleController extends Controller
{
    public function  pos(){ 
        $user = Auth::user();
        $users= User::all();
        $customers = customer::all();
        $products = Product::all();
        return view('adminpages.pos', ['userName' => $user->name,'userEmail' => $user->email],compact('users','customers','products'));
      }

      public function  salelist(){ 
        $user = Auth::user();
        $sales = Sale::with(['saleItems', 'user'])
            ->whereDate('created_at', Carbon::today())
            ->get();

        $saless = Sale::with(['saleItems', 'user'])->get();
        return view('adminpages.salelist', ['userName' => $user->name,'userEmail' => $user->email],compact('sales','saless'));
      }

      public function getProductDetails($id)
      {
          $product = Product::findOrFail($id);
          return response()->json([
              'item_name' => $product->item_name,
              'retail_rate' => $product->retail_rate,
              'purchase_rate' => $product->purchase_rate,
              'quantity' => $product->quantity 
          ]);
      }
      

public function getCustomersByUsername($username)
{
    if ($username === '1') {
        $customers = Customer::all();
    } else {
        $customers = Customer::where('assigned_user_id', $username)->get();
    }

    return response()->json([
        'customers' => $customers,
        'fixed_discount' => $customers->first()?->client_fixed_discount ?? null,
    ]);
}

public function getCustomerDiscount($customerId)
{
    $customer = Customer::find($customerId);
    return response()->json([
        'fixed_discount' => $customer?->client_fixed_discount ?? null
    ]);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'employee' => 'nullable|string',
        'customer_name' => 'nullable|string',
        'created_at' => 'nullable|date',
        'ref' => 'nullable|string',
        'total_items' => 'nullable|integer',
        'total' => 'nullable|numeric',
        'sale_type' => 'required|string',
        'payment_type' => 'required_if:sale_type,1|string|nullable',
        'discount' => 'nullable|numeric',
        'amount_after_discount' => 'nullable|numeric',
        'fixed_discount' => 'nullable|numeric',
        'amount_after_fix_discount' => 'nullable|numeric',
        'subtotal' => 'nullable|numeric',
        'items' => 'required|array',
    ]);

    $createdAt = $validated['created_at'] ?? Carbon::now();

    $sale = Sale::create([
        'employee' => $validated['employee'],
        'customer_name' => $validated['customer_name'],
        'created_at' => $createdAt,
        'ref' => $validated['ref'],
        'total_items' => $validated['total_items'],
        'total' => $validated['total'],
        'sale_type' => $validated['sale_type'],
        'payment_type' => $validated['sale_type'] == '1' ? $validated['payment_type'] : null,
        'discount' => $validated['discount'],
        'amount_after_discount' => $validated['amount_after_discount'],
        'fixed_discount' => $validated['fixed_discount'],
        'amount_after_fix_discount' => $validated['amount_after_fix_discount'],
        'subtotal' => $validated['subtotal'],
        'user_id' => auth()->id(),
        'status' => $validated['sale_type'] == '1' ? 'complete' : 'pending', 
    ]);

    

    foreach ($request->items as $item) {
        SaleItem::create([
            'sale_id' => $sale->id,
            'product_name' => $item['product_name'],
            'product_quantity' => $item['product_quantity'],
            'purchase_rate' => $item['purchase_rate'],
            'product_rate' => $item['product_rate'],
            'product_subtotal' => $item['product_subtotal'],
        ]);

        $product = Product::where('item_name', $item['product_name'])->first();
        if ($product) {
            $product->quantity -= $item['product_quantity'];
            $product->save();
        }
    }

    $totalProductRate = collect($request->items)->sum('purchase_rate');

    if ($sale->sale_type == '1') { 
        $paymentType = strtolower(trim($sale->payment_type)); 
        $cashAccountName = $paymentType === '2' ? 'Cash At Bank' : 'Cash In Hand';

        $cashAccount = AddAccount::whereRaw('LOWER(sub_head_name) = ?', [strtolower($cashAccountName)])->first();
        if ($cashAccount) {
            GrnAccount::create([
                'vendor_account_id' => $cashAccount->id,
                'sale_id' => $sale->id,
                'debit' => $sale->subtotal,
            ]);
        } else {
            \Log::warning("Account not found for: " . $cashAccountName);
        }

        $cashSalesAccount = AddAccount::where('sub_head_name', 'Cash Sales')->first();
        if ($cashSalesAccount) {
            GrnAccount::create([
                'vendor_account_id' => $cashSalesAccount->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => $sale->total,
            ]);
        }

        $CostOfGoodSoldAccount = AddAccount::where('sub_head_name', 'Cost Of Goods Sold')->first();
        if ($CostOfGoodSoldAccount) {
            GrnAccount::create([
                'vendor_account_id' => $CostOfGoodSoldAccount->id,
                'sale_id' => $sale->id,
                'debit' => $totalProductRate, 
            ]);
        }

        $inventory = AddAccount::where('sub_head_name', 'Inventory')->first();
        if ($inventory) {
            GrnAccount::create([
                'vendor_account_id' => $inventory->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => $totalProductRate, 
            ]);
        }

        $disGiven = AddAccount::where('sub_head_name', 'Discount Given')->first();
if ($disGiven) {
    $debitAmount = 0;
    if ($sale->discount) {
        $debitAmount += $sale->discount;
    }
    if ($sale->fixed_discount) {
        $debitAmount += $sale->fixed_discount;
    }

    GrnAccount::create([
        'vendor_account_id' => $disGiven->id,
        'sale_id' => $sale->id,
        'debit' => $debitAmount,
    ]);
}


        $taxPayable = AddAccount::where('sub_head_name', 'Tax Payable')->first();
        if ($taxPayable) {
            GrnAccount::create([
                'vendor_account_id' => $taxPayable->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => 0,
            ]);
        }

    } elseif ($sale->sale_type == '2') {
        $customerAccount = AddAccount::where('sub_head_name', $request->customer_name)->first();
        if ($customerAccount) {
            GrnAccount::create([
                'vendor_account_id' => $customerAccount->id,
                'sale_id' => $sale->id,
                'debit' => $sale->subtotal,
            ]);
        }

        $cashSalesAccount = AddAccount::where('sub_head_name', 'Credit Sales')->first();
        if ($cashSalesAccount) {
            GrnAccount::create([
                'vendor_account_id' => $cashSalesAccount->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => $sale->total,
            ]);
        }

        $CostOfGoodSoldAccount = AddAccount::where('sub_head_name', 'Cost Of Goods Sold')->first();
        if ($CostOfGoodSoldAccount) {
            GrnAccount::create([
                'vendor_account_id' => $CostOfGoodSoldAccount->id,
                'sale_id' => $sale->id,
                'debit' => $totalProductRate, 
            ]);
        }

        $inventory = AddAccount::where('sub_head_name', 'Inventory')->first();
        if ($inventory) {
            GrnAccount::create([
                'vendor_account_id' => $inventory->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => $totalProductRate, 
            ]);
        }

        $disGiven = AddAccount::where('sub_head_name', 'Discount Given')->first();
if ($disGiven) {
    $debitAmount = 0;
    if ($sale->discount) {
        $debitAmount += $sale->discount;
    }
    if ($sale->fixed_discount) {
        $debitAmount += $sale->fixed_discount;
    }

    GrnAccount::create([
        'vendor_account_id' => $disGiven->id,
        'sale_id' => $sale->id,
        'debit' => $debitAmount,
    ]);
}


        $taxPayable = AddAccount::where('sub_head_name', 'Tax Payable')->first();
        if ($taxPayable) {
            GrnAccount::create([
                'vendor_account_id' => $taxPayable->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => 0,
            ]);
        }
    }

    return response()->json(['message' => 'Sale recorded successfully']);
}



public function edit($id)
{
    $sale = Sale::with('saleItems')->findOrFail($id);
    $users= User::all();
    $customers = customer::all();
    $products = Product::all();
    $user = Auth::user();
    return view('adminpages.salesedit',['userName' => $user->name,'userEmail' => $user->email],compact('users','customers','products','sale')); 
}

public function getProductQuantity(Request $request)
{
    $product = Product::where('item_name', $request->product_name)->first();

    if ($product) {
        return response()->json(['quantity' => $product->quantity]);
    }

    return response()->json(['quantity' => 0], 404);
}




public function updateSale(Request $request, $saleId)
{
    $validated = $request->validate([
        'employee' => 'nullable|string',
        'customer_name' => 'nullable|string',
        'created_at' => 'nullable|date',
        'ref' => 'nullable|string',
        'total_items' => 'nullable|integer',
        'total' => 'nullable|numeric',
        'sale_type' => 'required|string',
        'payment_type' => 'string|nullable',
        'discount' => 'nullable|numeric',
        'amount_after_discount' => 'nullable|numeric',
        'fixed_discount' => 'nullable|numeric',
        'amount_after_fix_discount' => 'nullable|numeric',
        'subtotal' => 'nullable|numeric',
        'items' => 'required|array',
    ]);

    $sale = Sale::findOrFail($saleId);

    GrnAccount::where('sale_id', $sale->id)->delete();

    if ($validated['sale_type'] == '2') {
        $validated['payment_type'] = null;
    } elseif ($validated['sale_type'] == '1' && empty($validated['payment_type'])) {
        $validated['payment_type'] = 'Cash';
    }
    

    $sale->update([
        'employee' => $validated['employee'] ?? $sale->employee,
        'customer_name' => $validated['customer_name'] ?? $sale->customer_name,
        'created_at' => $validated['created_at'] ?? $sale->created_at,
        'ref' => $validated['ref'] ?? $sale->ref,
        'total_items' => $validated['total_items'] ?? $sale->total_items,
        'total' => $validated['total'] ?? $sale->total,
        'sale_type' => $validated['sale_type'],
        'payment_type' => ($validated['sale_type'] == '2') ? null : ($validated['payment_type'] ?? $sale->payment_type),
        'discount' => $validated['discount'] ?? $sale->discount,
        'amount_after_discount' => $validated['amount_after_discount'] ?? $sale->amount_after_discount,
        'fixed_discount' => $validated['fixed_discount'] ?? $sale->fixed_discount,
        'amount_after_fix_discount' => $validated['amount_after_fix_discount'] ?? $sale->amount_after_fix_discount,
        'subtotal' => $validated['subtotal'] ?? $sale->subtotal,
        'status' => $validated['sale_type'] == '1' ? 'complete' : 'pending',

    ]);

    SaleItem::where('sale_id', $sale->id)->delete();

    foreach ($validated['items'] as $item) {
        SaleItem::create([
            'sale_id' => $sale->id,
            'product_name' => $item['product_name'],
            'product_quantity' => $item['product_quantity'],
            'product_rate' => $item['product_rate'],
            'product_subtotal' => $item['product_subtotal'],
            'purchase_rate' => $item['purchase_rate'],
        ]);

        $product = Product::where('item_name', $item['product_name'])->first();
        if ($product) {
            $product->quantity -= $item['product_quantity'];
            $product->save();
        }
    }
    $totalProductRate = collect($request->items)->sum('purchase_rate');

    if ($sale->sale_type == '1') { 
        $paymentType = strtolower(trim($sale->payment_type)); 
        $cashAccountName = $paymentType === '2' ? 'Cash At Bank' : 'Cash In Hand';

        $cashAccount = AddAccount::whereRaw('LOWER(sub_head_name) = ?', [strtolower($cashAccountName)])->first();
        if ($cashAccount) {
            GrnAccount::create([
                'vendor_account_id' => $cashAccount->id,
                'sale_id' => $sale->id,
                'debit' => $sale->subtotal,
            ]);
        } else {
            \Log::warning("Account not found for: " . $cashAccountName);
        }

        $cashSalesAccount = AddAccount::where('sub_head_name', 'Cash Sales')->first();
        if ($cashSalesAccount) {
            GrnAccount::create([
                'vendor_account_id' => $cashSalesAccount->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => $sale->total,
            ]);
        }

        $CostOfGoodSoldAccount = AddAccount::where('sub_head_name', 'Cost Of Goods Sold')->first();
        if ($CostOfGoodSoldAccount) {
            GrnAccount::create([
                'vendor_account_id' => $CostOfGoodSoldAccount->id,
                'sale_id' => $sale->id,
                'debit' => $totalProductRate, 
            ]);
        }

        $inventory = AddAccount::where('sub_head_name', 'Inventory')->first();
        if ($inventory) {
            GrnAccount::create([
                'vendor_account_id' => $inventory->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => $totalProductRate, 
            ]);
        }

        $disGiven = AddAccount::where('sub_head_name', 'Discount Given')->first();
if ($disGiven) {
    $debitAmount = 0;
    if ($sale->discount) {
        $debitAmount += $sale->discount;
    }
    if ($sale->fixed_discount) {
        $debitAmount += $sale->fixed_discount;
    }

    GrnAccount::create([
        'vendor_account_id' => $disGiven->id,
        'sale_id' => $sale->id,
        'debit' => $debitAmount,
    ]);
}


        $taxPayable = AddAccount::where('sub_head_name', 'Tax Payable')->first();
        if ($taxPayable) {
            GrnAccount::create([
                'vendor_account_id' => $taxPayable->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => 0,
            ]);
        }

    } elseif ($sale->sale_type == '2') {
        $customerAccount = AddAccount::where('sub_head_name', $request->customer_name)->first();
        if ($customerAccount) {
            GrnAccount::create([
                'vendor_account_id' => $customerAccount->id,
                'sale_id' => $sale->id,
                'debit' => $sale->subtotal,
            ]);
        }

        $cashSalesAccount = AddAccount::where('sub_head_name', 'Credit Sales')->first();
        if ($cashSalesAccount) {
            GrnAccount::create([
                'vendor_account_id' => $cashSalesAccount->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => $sale->total,
            ]);
        }

        $CostOfGoodSoldAccount = AddAccount::where('sub_head_name', 'Cost Of Goods Sold')->first();
        if ($CostOfGoodSoldAccount) {
            GrnAccount::create([
                'vendor_account_id' => $CostOfGoodSoldAccount->id,
                'sale_id' => $sale->id,
                'debit' => $totalProductRate, 
            ]);
        }

        $inventory = AddAccount::where('sub_head_name', 'Inventory')->first();
        if ($inventory) {
            GrnAccount::create([
                'vendor_account_id' => $inventory->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => $totalProductRate, 
            ]);
        }

        $disGiven = AddAccount::where('sub_head_name', 'Discount Given')->first();
if ($disGiven) {
    $debitAmount = 0;
    if ($sale->discount) {
        $debitAmount += $sale->discount;
    }
    if ($sale->fixed_discount) {
        $debitAmount += $sale->fixed_discount;
    }

    GrnAccount::create([
        'vendor_account_id' => $disGiven->id,
        'sale_id' => $sale->id,
        'debit' => $debitAmount,
    ]);
}
        $taxPayable = AddAccount::where('sub_head_name', 'Tax Payable')->first();
        if ($taxPayable) {
            GrnAccount::create([
                'vendor_account_id' => $taxPayable->id,
                'sale_id' => $sale->id,
                'vendor_net_amount' => 0,
            ]);
        }
    }

    return response()->json(['message' => 'Sale updated successfully!']);
}


public function deleteSale($saleId)
{
    $sale = Sale::findOrFail($saleId);
    SaleItem::where('sale_id', $saleId)->delete();
    GrnAccount::where('sale_id', $saleId)->delete();
    $sale->delete();
    return response()->json(['message' => 'Sale and related records deleted successfully!']);
}


public function saleinvoice($id)
{
    $sale = Sale::with(['saleItems', 'user'])->findOrFail($id);

    return view('adminpages.invoice', [
        'sale' => $sale,
        'userName' => $sale->user->name,
        'userEmail' => $sale->user->email,
        'saleItems' => $sale->saleitems,
    ]);
}

public function saleprintinvoice($id)
{
    $sale = Sale::with(['saleItems', 'user'])->findOrFail($id);

    return view('adminpages.saleprintinvoice', [
        'sale' => $sale,
        'userName' => $sale->user->name,
        'userEmail' => $sale->user->email,
        'saleItems' => $sale->saleitems,
    ]);
}




public function completeSale(Request $request)
{
    $request->validate([
        'sale_id' => 'required|exists:sales,id',
        'payment_type' => 'required|in:1,2',
        'customer_name' => 'required|string',
        'subtotal' => 'required|numeric',
    ]);

    $sale = Sale::findOrFail($request->sale_id);

    if ($sale->sale_type !== 'credit' || $sale->status !== 'pending') {
        return response()->json(['message' => 'Invalid sale operation.'], 400);
    }

    $sale->payment_type = $request->payment_type;
    $sale->status = 'complete';
    $sale->save();

    $subtotal = $request->subtotal;

    $customerAccount = AddAccount::firstOrCreate(
        ['sub_head_name' => $request->customer_name],
        ['head_name' => 'Customer', 'account_type' => 'vendor']
    );

    $cashAccountName = $request->payment_type == '2' ? 'Cash At Bank' : 'Cash In Hand';
    $cashAccount = AddAccount::whereRaw('LOWER(sub_head_name) = ?', [strtolower($cashAccountName)])->first();

    if (!$cashAccount) {
        return response()->json(['message' => 'Cash account not found.'], 400);
    }

    GrnAccount::create([
        'vendor_account_id' => $customerAccount->id,
        'sale_id' => $sale->id,
        'vendor_name' => $request->customer_name,
        'vendor_net_amount' => $subtotal,
        'complete' => 'complete',
    ]);

    GrnAccount::create([
        'vendor_account_id' => $cashAccount->id,
        'sale_id' => $sale->id,
        'vendor_name' => $cashAccountName,
        'debit' => $subtotal,
        'credit' => $subtotal,
        'complete' => 'complete',
    ]);

    return response()->json(['message' => 'Sale marked complete. GRN entries created.']);
}


public function salelistsearch(Request $request)
{
    $user = Auth::user();
    $query = Sale::with(['user']);

    if ($request->filled('from_date') && $request->filled('to_date')) {
        $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
    }

    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('customer_name')) {
        $query->where('customer_name', $request->customer_name);
    }

    $sales = $query->get();
    $saless = Sale::with(['saleItems', 'user'])->get();

    return view('adminpages.salelist', compact('sales','saless'))
        ->with([
            'userName' => $user->name,
            'userEmail' => $user->email,
        ]);
}




}
