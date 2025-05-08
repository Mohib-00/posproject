<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'area_name',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'area_id');
    }
}
