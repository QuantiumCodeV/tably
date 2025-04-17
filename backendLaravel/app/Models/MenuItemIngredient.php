<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItemIngredient extends Model
{
    use HasFactory;

    // Указываем имя таблицы явно
    protected $table = 'menu_item_ingredients';

    protected $fillable = [
        'menu_item_id', // Обновляем имя столбца с menu_id на menu_item_id
        'ingredient_id',
        'quantity_required',
    ];

     // Исправляем отношение с блюдом
    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_item_id');
    }
    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class);
    }
}
