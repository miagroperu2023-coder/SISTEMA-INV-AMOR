<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SizeSet extends Model
{
    use HasFactory;

     protected $fillable = [
        'tipo',
        'valor',
        'orden',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'size_id');
    }
}
