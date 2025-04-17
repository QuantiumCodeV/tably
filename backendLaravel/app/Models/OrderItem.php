<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_item_id', 
        'quantity',
        'price',
    ];

    // Связь с заказом
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Связь с блюдом 
    public function menuItem()
    {
        return $this->belongsTo(Menu::class, 'menu_item_id');
    }
}
