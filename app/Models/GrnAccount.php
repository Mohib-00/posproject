<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrnAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_account_id',
        'vendor_net_amount',
        'discount',
        'purchase_id',
        'sale_id',
        'debit',
        'complete',
        'grn',
        'payment',
        'voucher_id',
        'voucher'
    ];

    public function vendorAccount()
    {
        return $this->belongsTo(AddAccount::class, 'vendor_account_id');
    }

    public function purchase()
{
    return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
}

public function sale()
{
    return $this->belongsTo(Sale::class, 'sale_id', 'id');
}


public function voucher()
{
    return $this->belongsTo(Voucher::class, 'voucher_id', 'id');
}



}

