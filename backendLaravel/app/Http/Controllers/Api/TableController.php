<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function getTableById($id)
    {
        $table = Table::findOrFail($id);
        
        $table->load([
            'restaurant', 
            'restaurant.menuCategories', 
            'restaurant.menuCategories.menuItems',
            'restaurant.menuCategories.menuItems.ingredients' 
        ]);
        
        return response()->json([
            'table' => [
                'id' => $table->id,
                'number' => $table->table_number,
                'capacity' => $table->capacity,
            ],
            'restaurant' => [
                'id' => $table->restaurant->id,
                'name' => $table->restaurant->name,
                'description' => $table->restaurant->description,
                'logo' => $table->restaurant->logo_url ?? null,
                'address' => $table->restaurant->address,
                'phone' => $table->restaurant->phone,
            ],
            'menu_categories' => $table->restaurant->menuCategories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'menu_items' => $category->menuItems->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                            'description' => $item->description,
                            'price' => $item->price,
                            'image' => $item->image_url ? url('storage/' . $item->image_url) : null,
                            'is_available' => $item->is_available ?? true,
                            // Добавляем ингредиенты
                            'ingredients' => $item->ingredients->map(function ($ingredient) {
                                return [
                                    'id' => $ingredient->id,
                                    'name' => $ingredient->name,
                                    'quantity' => $ingredient->pivot->quantity ?? null,
                                    'unit' => $ingredient->pivot->unit ?? null,
                                ];
                            }),
                        ];
                    }),
                ];
            }),
        ]);
    }
} 