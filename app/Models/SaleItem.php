<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id', 'product_name', 'product_quantity', 'product_rate', 'product_subtotal','purchase_rate'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class,'sale_id', 'id');
    }

    public function product()
{
    return $this->belongsTo(Product::class);
}

}

