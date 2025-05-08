<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee', 'customer_name', 'created_at', 'ref', 'total_items', 'total',
        'sale_type', 'payment_type', 'discount', 'amount_after_discount', 'fixed_discount',
        'amount_after_fix_discount', 'subtotal','user_id','sale_return','status','purchase_rate'
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class,'sale_id','id');
    }

    public function grnAccounts()
{
    return $this->hasMany(GrnAccount::class, 'sale_id', 'id');
}

public function user()
{
    return $this->belongsTo(User::class,'user_id','id');
}


}
