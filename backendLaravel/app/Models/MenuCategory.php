<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'name',
    ];

    // Связь с рестораном
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Связь с блюдами
    public function menuItems()
    {
        return $this->hasMany(Menu::class, 'category_id');
    }
}
