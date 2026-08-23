<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'total',
        'user_id'
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payment()
    {
        return $this->hasOne(SalePayment::class);
    }
}
