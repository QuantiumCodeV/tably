<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    use HasFactory;
    // Указываем имя таблицы явно
    protected $table = 'menu';
    protected $fillable = [
        'restaurant_id',
        'category_id',
        'name',
        'description',
        'price',
        'image_url',
        'is_available',
    ];

    // Связь с рестораном
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Связь с категорией меню
    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function menuIngredients(): HasMany
    {
        return $this->hasMany(MenuItemIngredient::class);
    }

    // Связь с ингредиентами через MenuItemIngredients
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'menu_item_ingredients', 'menu_item_id', 'ingredient_id')
            ->withTimestamps();
    }

    // Связь с заказами через OrderItems
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot('quantity', 'price');
    }
}
