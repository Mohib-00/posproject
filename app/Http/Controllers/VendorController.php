<?php

namespace App\Http\Controllers;

use App\Models\Vendors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function addvendor(){ 
        $user = Auth::user();      
        $vendors = Vendors::orderBy('created_at', 'desc')
        ->get();    
        return view('adminpages.vendors', ['userName' => $user->name,'userEmail' => $user->email],compact('vendors'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|unique:vendors,name',
                'mobile' => 'required',
                'address' => 'required',
            ]);
    
            $vendor = new Vendors();
            $vendor->name = $request->name;
            $vendor->mobile = $request->mobile;
            $vendor->address = $request->address;
            $vendor->user_id = Auth::id();
            $vendor->save();
    
            \App\Models\AddAccount::create([
                'head_name' => 'Accounts Payable',
                'sub_head_name' => $vendor->name,
                'child' => 'Accounts Payable',
            ]);
    
            $user = \App\Models\User::find($vendor->user_id); 
    
            return response()->json([
                'success' => true, 
                'vendor' => $vendor, 
                'user_name' => $user ? $user->name : 'Unknown'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    
    
    

public function show($id)
{
    $vendor = Vendors::find($id);

    if (!$vendor) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'vendor' => $vendor
    ]);
}


public function update(Request $request, $id)
{
    $vendor = Vendors::findOrFail($id);   

    $validator = Validator::make($request->all(), [
      'name' => 'nullable|string|max:255',
      'mobile' => 'nullable|string|max:255',
      'address' => 'nullable',
  ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

     
    if ($request->has('name')) {
        $vendor->name = $request->name;
    }
    if ($request->has('mobile')) {
        $vendor->mobile = $request->mobile;
    }
    if ($request->has('address')) {
        $vendor->address = $request->address;
    }
    $user_name = $vendor->user ? $vendor->user->name : 'N/A';
   
     $vendor->save();

    return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'vendor' => $vendor,
        'user_name' => $user_name,
    ], 200);
}

public function deletevendor(Request $request)
{
    $vendor = Vendors::find($request->vendor_id);

    if ($vendor) {
        $vendor->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    return response()->json(['success' => false, 'message' => 'Not found']);
}

}
