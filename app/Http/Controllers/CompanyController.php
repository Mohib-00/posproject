<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function addcompany(){ 
        $user = Auth::user();      
        $companys = Company::orderBy('created_at', 'desc')
        ->get();    
        return view('adminpages.company', [
            'userName' => $user->name,
            'userEmail' => $user->email,
            'companys' => $companys
        ]);
            }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'designation_name' => 'required',
            ]);
    
            $company = new Company();
            $company->designation_name = $request->designation_name;
            $company->user_id = Auth::id();
            
            $company->save();
    
            $user = \App\Models\User::find($company->user_id); 
    
            return response()->json([
                'success' => true, 
                'company' => $company, 
                'user_name' => $user ? $user->name : 'Unknown'
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    
    

public function show($id)
{
    $company = Company::find($id);

    if (!$company) {
        return response()->json([
            'success' => false,
            'message' => 'Not found'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'company' => $company
    ]);
}


public function update(Request $request, $id)
{
    $company = Company::findOrFail($id);   

    $validator = Validator::make($request->all(), [
      'designation_name' => 'nullable|string|max:255',
    
  ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

     
    if ($request->has('designation_name')) {
        $company->designation_name = $request->designation_name;
    }
  
    $user_name = $company->user ? $company->user->name : 'N/A';
   
     $company->save();

    return response()->json([
        'success' => true,
        'message' => 'Updated successfully!',
        'company' => $company,
        'user_name' => $user_name,
    ], 200);
}

public function deletecompany(Request $request)
{
    $company = Company::find($request->company_id);

    if ($company) {
        $company->delete();
        return response()->json(['success' => true, 'message' => 'Deleted successfully']);
    }

    return response()->json(['success' => false, 'message' => 'Not found']);
}
}
