<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name', 
        'logo_url',
        'city',
        'address',
        'description',
    ];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь со столами
    public function tables()
    {
        return $this->hasMany(Table::class);
    }

    // Связь с меню
    public function menu()
    {
        return $this->hasMany(Menu::class);
    }

    // Связь с категориями меню
    public function menuCategories()
    {
        return $this->hasMany(MenuCategory::class);
    }

    public function getLogoUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        
        return Storage::disk('public')->url($value);    
    }
}
