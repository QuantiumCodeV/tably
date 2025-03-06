<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_id',
        'quantity_added', 
        'supply_date',
    ];

    // Связь с ингредиентом
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }
}
