<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria_id',
        'imagen',
        'user_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function stockTotal()
    {
        return $this->variants()->sum('stock');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
