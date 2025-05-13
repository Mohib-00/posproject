<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function customer(){ 
        $user = Auth::user();      

    $customers = Customer::where('block', false) 
    ->orderBy('created_at', 'desc')
    ->get();
    
        $areas= Area::all();
        $users=User::all();
        return view('adminpages.customers', ['userName' => $user->name,'userEmail' => $user->email],compact('customers','areas','users'));
    }

    public function blockedclientlist(){ 
    $user = Auth::user();      

    $customers = Customer::where('block', true) 
    ->orderBy('created_at', 'desc')
    ->get();
    
        $areas= Area::all();
        $users=User::all();
        return view('adminpages.blockedclientlist', ['userName' => $user->name,'userEmail' => $user->email],compact('customers','areas','users'));
    }

    public function addcustomer(){ 
        $user = Auth::user();      
        $areas= Area::all();
        $users=User::all();
        return view('adminpages.addcustomers', ['userName' => $user->name,'userEmail' => $user->email],compact('areas','users'));
    }

   public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'customer_name' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = Customer::whereRaw('LOWER(customer_name) = ?', [strtolower($value)])->exists();
                    if ($exists) {
                        $fail('The customer name has already been taken.');
                    }
                }
            ],
            'area_id' => 'nullable',
            'client_type' => 'nullable|string|max:255',
            'assigned_user_id' => 'nullable',
            'phone_1' => 'nullable|string|max:255',
            'phone_2' => 'nullable|string|max:255',
            'client_gender' => 'nullable|string|max:255',
            'client_cnic' => 'nullable|string|max:255',
            'client_father_name' => 'nullable|string|max:255',
            'client_residence' => 'nullable|string|max:255',
            'client_occupation' => 'nullable|string|max:255',
            'client_salary' => 'nullable|numeric',
            'client_fixed_discount' => 'nullable|numeric',
            'distributor_fix_margin' => 'nullable|numeric',
            'client_permanent_address' => 'nullable|string|max:255',
            'client_residential_address' => 'nullable|string|max:255',
            'client_office_address' => 'nullable|string|max:255',
        ]);

        $customer = new Customer();
        $customer->customer_name = $validatedData['customer_name'] ?? null;
        $customer->area_id = $validatedData['area_id'] ?? null;
        $customer->client_type = $validatedData['client_type'] ?? null;
        $customer->assigned_user_id = $validatedData['assigned_user_id'] ?? null;
        $customer->phone_1 = $validatedData['phone_1'] ?? null;
        $customer->phone_2 = $validatedData['phone_2'] ?? null;
        $customer->client_gender = $validatedData['client_gender'] ?? null;
        $customer->client_cnic = $validatedData['client_cnic'] ?? null;
        $customer->client_father_name = $validatedData['client_father_name'] ?? null;
        $customer->client_residence = $validatedData['client_residence'] ?? null;
        $customer->client_occupation = $validatedData['client_occupation'] ?? null;
        $customer->client_salary = $validatedData['client_salary'] ?? null;
        $customer->client_fixed_discount = $validatedData['client_fixed_discount'] ?? null;
        $customer->distributor_fix_margin = $validatedData['distributor_fix_margin'] ?? null;
        $customer->client_permanent_address = $validatedData['client_permanent_address'] ?? null;
        $customer->client_residential_address = $validatedData['client_residential_address'] ?? null;
        $customer->client_office_address = $validatedData['client_office_address'] ?? null;

        $customer->save();

        \App\Models\AddAccount::create([
            'head_name' => 'Accounts Receiveable',
            'sub_head_name' => $customer->customer_name,
            'child' => 'Accounts Receiveable',
        ]);

        return response()->json([
            'success' => true,
            'customer' => $customer,
        ], 201);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}


public function show($id)
{
    $customer = customer::find($id);

    if (!$customer) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'customer' => $customer
    ]);
}


public function update(Request $request, $id)
{
    try {
        $customer = Customer::findOrFail($id);

        $validator = Validator::make($request->all(), [
              'customer_name' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = Customer::whereRaw('LOWER(customer_name) = ?', [strtolower($value)])->exists();
                    if ($exists) {
                        $fail('The customer name has already been taken.');
                    }
                }
            ],
            'area_id' => 'nullable',
            'client_type' => 'nullable|string|max:255',
            'assigned_user_id' => 'nullable
            ',
            'phone_1' => 'nullable|string|max:255',
            'phone_2' => 'nullable|string|max:255',
            'client_gender' => 'nullable|string|max:255',
            'client_cnic' => 'nullable|string|max:255',
            'client_father_name' => 'nullable|string|max:255',
            'client_residence' => 'nullable|string|max:255',
            'client_occupation' => 'nullable|string|max:255',
            'client_salary' => 'nullable|numeric',
            'client_fixed_discount' => 'nullable|numeric',
            'distributor_fix_margin' => 'nullable|numeric',
            'client_permanent_address' => 'nullable|string|max:255',
            'client_residential_address' => 'nullable|string|max:255',
            'client_office_address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('customer_name')) {
            $customer->customer_name = $request->customer_name;
        }
        if ($request->has('area_id')) {
            $customer->area_id = $request->area_id;
        }
        if ($request->has('client_type')) {
            $customer->client_type = $request->client_type;
        }
        if ($request->has('assigned_user_id')) {
            $customer->assigned_user_id = $request->assigned_user_id;
        }
        if ($request->has('phone_1')) {
            $customer->phone_1 = $request->phone_1;
        }
        if ($request->has('phone_2')) {
            $customer->phone_2 = $request->phone_2;
        }
        if ($request->has('client_gender')) {
            $customer->client_gender = $request->client_gender;
        }
        if ($request->has('client_cnic')) {
            $customer->client_cnic = $request->client_cnic;
        }
        if ($request->has('client_father_name')) {
            $customer->client_father_name = $request->client_father_name;
        }
        if ($request->has('client_residence')) {
            $customer->client_residence = $request->client_residence;
        }
        if ($request->has('client_occupation')) {
            $customer->client_occupation = $request->client_occupation;
        }
        if ($request->has('client_salary')) {
            $customer->client_salary = $request->client_salary;
        }
        if ($request->has('client_fixed_discount')) {
            $customer->client_fixed_discount = $request->client_fixed_discount;
        }
        if ($request->has('distributor_fix_margin')) {
            $customer->distributor_fix_margin = $request->distributor_fix_margin;
        }
        if ($request->has('client_permanent_address')) {
            $customer->client_permanent_address = $request->client_permanent_address;
        }
        if ($request->has('client_residential_address')) {
            $customer->client_residential_address = $request->client_residential_address;
        }
        if ($request->has('client_office_address')) {
            $customer->client_office_address = $request->client_office_address;
        }

        $customer->save();

        $user_name = $customer->assignedUser ? $customer->assignedUser->name : 'N/A';

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully!',
            'customer' => $customer,
        ], 200);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

public function deletecustomer(Request $request)
{
    $customer = Customer::find($request->customer_id);

    if ($customer) {
        \App\Models\AddAccount::where('sub_head_name', $customer->customer_name)->delete();

        $customer->delete();

        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    return response()->json(['success' => false, 'message' => 'Customer not found']);
}



public function blockCustomer($id)
{
    $customer = Customer::find($id);

    if (!$customer) {
        return response()->json(['success' => false, 'message' => 'Customer not found']);
    }

    $customer->block = 1;
    $customer->save();

    return response()->json(['success' => true, 'message' => 'Customer blocked successfully']);
}

public function unblock($id)
{
    $customer = Customer::findOrFail($id);
    $customer->block = false;
    $customer->save();

    return response()->json(['success' => true, 'message' => 'Customer unblocked successfully']);
}


}
