<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function addproduct(){ 
        $user = Auth::user();      
        $categorys = Category::all();
        $products = Product::all();
        $brands = Company::all();  
        $subs = SubCategory::all();
        return view('adminpages.products', ['userName' => $user->name,'userEmail' => $user->email],compact('categorys','brands','products','subs'));
    }


    public function productpricelist(){ 
        $user = Auth::user();      
        $products = Product::all();
        return view('adminpages.productsprice', ['userName' => $user->name,'userEmail' => $user->email],compact('products'));
    }

    public function productimport(){ 
        $user = Auth::user();      
        return view('adminpages.import', ['userName' => $user->name,'userEmail' => $user->email]);
    }
    
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'brand_name' => 'nullable|string',
                'category_name' => 'nullable|string',
                'subcategory_name' => 'nullable|string',
                'item_name' => 'nullable|string',
                'barcode' => 'nullable|string',
                'purchase_rate' => 'nullable|numeric',
                'retail_rate' => 'nullable|numeric',
                'gram' => 'nullable|numeric',
                'quantity' => 'nullable|integer',
            ]);
            
            if (!$request->quantity || $request->quantity <= 0) {
                throw new \Exception("Quantity must be greater than zero.");
            }
    
            $singlePurchaseRate = $request->purchase_rate / $request->quantity;
            $singleRetailRate = $request->retail_rate / $request->quantity;
    
            $product = new Product();
            $product->brand_name = $request->brand_name;
            $product->category_name = $request->category_name;
            $product->subcategory_name = $request->subcategory_name;
            $product->item_name = $request->item_name;
            $product->barcode = $request->barcode;
            $product->purchase_rate = $request->purchase_rate;
            $product->retail_rate = $request->retail_rate;
            $product->gram = $request->gram;
            $product->quantity = $request->quantity;
            $product->single_purchase_rate = $singlePurchaseRate;
            $product->single_retail_rate = $singleRetailRate;
            $product->user_id = Auth::id();
            
            $product->save();
    
            $user = \App\Models\User::find($product->user_id);
    
            return response()->json([
                'success' => true,
                'product' => $product,
                'user_name' => $user ? $user->name : 'Unknown'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    
    
    
    
    

public function show($id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'product' => $product
    ]);
}


public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);   

    $validator = Validator::make($request->all(), [
        'brand_name' => 'nullable|string|max:255',
        'category_name' => 'nullable|string|max:255',
        'subcategory_name' => 'nullable|string|max:255',
        'item_name' => 'nullable|string|max:255',
        'barcode' => 'nullable|string|max:255',
        'purchase_rate' => 'nullable|numeric',
        'retail_rate' => 'nullable|numeric',
        'gram' => 'nullable|numeric',
        'quantity' => 'nullable|integer',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    if ($request->has('quantity') && $request->quantity > 0) {
        $singlePurchaseRate = $request->purchase_rate / $request->quantity;
        $singleRetailRate = $request->retail_rate / $request->quantity;
    } else {
        $singlePurchaseRate = $product->single_purchase_rate; 
        $singleRetailRate = $product->single_retail_rate; 
    }

    if ($request->has('brand_name')) {
        $product->brand_name = $request->brand_name;
    }
    if ($request->has('category_name')) {
        $product->category_name = $request->category_name;
    }
    if ($request->has('subcategory_name')) {
        $product->subcategory_name = $request->subcategory_name;
    }
    if ($request->has('item_name')) {
        $product->item_name = $request->item_name;
    }
    if ($request->has('barcode')) {
        $product->barcode = $request->barcode;
    }
    if ($request->has('purchase_rate')) {
        $product->purchase_rate = $request->purchase_rate;
    }
    if ($request->has('retail_rate')) {
        $product->retail_rate = $request->retail_rate;
    }
    if ($request->has('gram')) {
        $product->gram = $request->gram;
    }
    if ($request->has('quantity')) {
        $product->quantity = $request->quantity;
    }

    $product->single_purchase_rate = $singlePurchaseRate;
    $product->single_retail_rate = $singleRetailRate;

    $product->save();

    $user_name = $product->user ? $product->user->name : 'N/A';

    return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'product' => $product,
        'user_name' => $user_name,
    ], 200);
}




public function deleteproduct(Request $request)
{
    $product = Product::find($request->product_id);

    if ($product) {
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    return response()->json(['success' => false, 'message' => 'Not found']);
}

public function addOpeningQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'opening_qty' => 'required|numeric|min:0'
        ]);

        $product = Product::find($request->product_id);

        $product->opening_quantity = $request->opening_qty;
        $product->save();

        return response()->json(['success' => true]);
    }

    public function updateOpeningQuantity(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'opening_qty' => 'required|numeric|min:0'
    ]);

    $product = Product::find($request->product_id);
    $product->opening_quantity = $request->opening_qty;
    $product->save();

    return response()->json(['success' => true]);
}

public function importCSV(Request $request)
{
    if (!$request->hasFile('excelFile')) {
        return back()->with('error', 'No file selected');
    }

    $file = $request->file('excelFile');

    if ($file->getClientOriginalExtension() !== 'csv') {
        return back()->with('error', 'Only CSV files are allowed');
    }

    $handle = fopen($file->getRealPath(), 'r');
    $header = true;

    while (($row = fgetcsv($handle, 1000, ',')) !== false) {
        if ($header) {
            $header = false;
            continue;
        }

        if (empty(array_filter($row))) {
            continue;
        }

        if (count($row) < 9) {
            continue;
        }

        $purchase_rate = is_numeric($row[5]) ? $row[5] : 0;
        $retail_rate   = is_numeric($row[6]) ? $row[6] : 0;
        $gram          = is_numeric($row[7]) ? $row[7] : 0;
        $quantity      = is_numeric($row[8]) ? $row[8] : 0;

        $single_purchase_rate = $quantity > 0 ? $purchase_rate / $quantity : 0;
        $single_retail_rate   = $quantity > 0 ? $retail_rate / $quantity : 0;

        Product::create([
            'brand_name'             => $row[0] ?? null,
            'category_name'          => $row[1] ?? null,
            'subcategory_name'       => $row[2] ?? null,
            'item_name'              => $row[3] ?? null,
            'barcode'                => $row[4] ?? null,
            'purchase_rate'          => $purchase_rate,
            'retail_rate'            => $retail_rate,
            'gram'                   => $gram,
            'quantity'               => $quantity,
            'single_purchase_rate'   => $single_purchase_rate,  
            'single_retail_rate'     => $single_retail_rate,   
            'user_id'                => Auth::id(),            
        ]);
    }

    fclose($handle);

    return back()->with('success', 'CSV imported successfully!');
}







public function getUpdatedPrice($productId, Request $request)
{
    $product = Product::find($productId);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found'
        ]);
    }

    $quantity = (int) $request->query('quantity', 1);
    $price = $product->price * $quantity; 
    $retailRate = $product->retail_rate * $quantity; 
    $wholesaleRate = $product->wholesale_rate * $quantity; 
    $miniWholesaleRate = $product->mini_whole_rate * $quantity; 
    $typeARate = $product->type_a_rate * $quantity; 
    $typeBRate = $product->type_b_rate * $quantity; 
    $typeCRate = $product->type_c_rate * $quantity; 

    $amount = $price; 

    return response()->json([
        'success' => true,
        'product' => [
            'price' => $price,
            'retail_rate' => $retailRate,
            'wholesale_rate' => $wholesaleRate,
            'mini_whole_rate' => $miniWholesaleRate,
            'type_a_rate' => $typeARate,
            'type_b_rate' => $typeBRate,
            'type_c_rate' => $typeCRate,
            'amount' => $amount
        ]
    ]);
}
public function getProductData($id)
{
    $product = Product::find($id);

    if ($product) {
        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Product not found'
    ]);

}

public function updateInline(Request $request)
{
    $product = Product::find($request->id);

    if (!$product) {
        return response()->json(['status' => 'error', 'message' => 'Product not found.']);
    }

    $column = $request->column;
    $value = $request->value;

    if (!in_array($column, ['quantity', 'purchase_rate', 'retail_rate', 'item_name', 'barcode'])) {
        return response()->json(['status' => 'error', 'message' => 'Invalid column.']);
    }

    $previous_quantity = $product->quantity;
    $previous_purchase_rate = $product->purchase_rate;
    $previous_retail_rate = $product->retail_rate;

    $product->$column = $value;

    if ($column == 'quantity') {
        $new_quantity = $value;

        if ($new_quantity > 0) {
            $product->purchase_rate = $previous_purchase_rate * ($new_quantity / $previous_quantity);
            $product->retail_rate = $previous_retail_rate * ($new_quantity / $previous_quantity);
        }

        $product->single_purchase_rate = $new_quantity > 0 ? $product->purchase_rate / $new_quantity : 0;
        $product->single_retail_rate = $new_quantity > 0 ? $product->retail_rate / $new_quantity : 0;
    }

    if (in_array($column, ['purchase_rate', 'retail_rate'])) {
        $purchase_rate = $product->purchase_rate;
        $retail_rate = $product->retail_rate;

        $product->single_purchase_rate = $product->quantity > 0 ? $purchase_rate / $product->quantity : 0;
        $product->single_retail_rate = $product->quantity > 0 ? $retail_rate / $product->quantity : 0;
    }

    $product->save();

    return response()->json([
        'status' => 'success', 
        'message' => 'Product updated successfully.',
        'single_purchase_rate' => $product->single_purchase_rate, 
        'single_retail_rate' => $product->single_retail_rate, 
        'purchase_rate' => $product->purchase_rate,
        'retail_rate' => $product->retail_rate 
    ]);
}



public function getProduct($id)
{
    $product = Product::findOrFail($id);

    $quantity = $product->quantity;
    $purchaseRate = $product->purchase_rate;
    $retailRate = $product->retail_rate;
    $singlePurchaseRate = $product->single_purchase_rate;
    $singleRetailRate = $product->single_retail_rate;

    return response()->json([
        'success' => true,
        'data' => [
            'quantity' => $quantity,
            'purchase_rate' => $purchaseRate,
            'retail_rate' => $retailRate,
            'single_purchase_rate' => $singlePurchaseRate,
            'single_retail_rate' => $singleRetailRate,
        ]
    ]);
}





}

