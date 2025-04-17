<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'session_token', 
        'started_at',
        'expires_at',
        'status'
    ];

    // Связь со столом
    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
