<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity_required', 
    ];

    // Связь с поставками
    public function supplies()
    {
        return $this->hasMany(Supply::class, 'ingredient_id');
    }

    // Связь с блюдами через MenuItemIngredients 
    public function menuIngredients(): HasMany
    {
        return $this->hasMany(MenuItemIngredient::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_item_ingredients', 'ingredient_id', 'menu_item_id', 'quantity_required')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
