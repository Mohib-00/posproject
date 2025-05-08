<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_id',
        'account',
        'balance',
        'narration',
        'amount',
    ];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class,'voucher_id','id');
    }
}
