<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'total_price',
        'status',
    ];

    // Связь со столом
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    // Связь с блюдами через OrderItems
    public function menuItems()
    {
        return $this->belongsToMany(Menu::class, 'order_items')
                    ->withPivot('quantity', 'price');
    }
}
