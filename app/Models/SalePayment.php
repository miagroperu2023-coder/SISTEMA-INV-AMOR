<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'tipo_pago',
        'monto',
    ];

    public function sale() // esta es la que faltaba
    {
        return $this->belongsTo(Sale::class);
    }
}
