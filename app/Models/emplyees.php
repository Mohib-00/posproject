<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class emplyees extends Model
{
    protected $fillable = [
        'employee_name', 
        'area_id', 
        'assigned_user_id', 
        'phone_1', 
        'phone_2', 
        'client_gender', 
        'client_cnic', 
        'client_father_name', 
        'client_residence', 
        'client_salary', 
        'client_permanent_address', 
        'client_residential_address', 
    ];
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }
}
