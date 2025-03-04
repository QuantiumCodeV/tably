<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'table_number',
        'capacity',
        'qr_code_url',
    ];

    // Автоматически генерировать QR-код при создании
    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($table) {
            // Генерируем QR-код после создания записи
            $table->generateAndSaveQrCode();
        });
    }

    // Генерация и сохранение QR-кода
    public function generateAndSaveQrCode()
    {
        // URL для QR-кода
        $url = env('FRONTEND_URL') . '/table/' . $this->id;
        
        // Генерируем QR-код
        $qrCode = QrCode::format('png')
            ->size(300)
            ->errorCorrection('H')
            ->margin(1)
            ->generate($url);
        
        // Путь для сохранения QR-кода
        $filename = 'qrcodes/table_' . $this->id . '_' . time() . '.png';
        
        // Сохраняем QR-код в хранилище
        Storage::disk('public')->put($filename, $qrCode);
        
        // Сохраняем путь к QR-коду в базе данных
        $this->qr_code_url = Storage::disk('public')->url($filename);
        $this->save();
        
        return $this->qr_code_url;
    }

    // Получить URL QR-кода
    public function getQrCodeUrl()
    {
        // Если QR-код не существует, генерируем его
        if (empty($this->qr_code_url) || !Storage::disk('public')->exists(str_replace('/storage/', '', $this->qr_code_url))) {
            return $this->generateAndSaveQrCode();
        }
        
        return $this->qr_code_url;
    }

    // Связь с рестораном
    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    // Связь с активными сессиями
    public function activeSessions()
    {
        return $this->hasMany(ActiveSession::class);
    }

    // Связь с заказами
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getTableUrl(): string
    {
        return env('FRONTEND_URL') . '/table/' . $this->id;
    }
}
