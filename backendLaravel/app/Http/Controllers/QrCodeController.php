<?php

namespace App\Http\Controllers;

use App\Models\Table;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use App\Models\ActiveSession;
use Illuminate\Support\Facades\Storage;

class QrCodeController extends Controller
{
    public function generateForTable(Table $table)
    {
        // Проверка доступа (только владелец ресторана или админ)
        if (!auth()->user()->is_admin && $table->restaurant->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $frontendUrl = env('FRONTEND_URL') . '/table/' . $table->id;

        // Генерируем QR-код
        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->errorCorrection('H')
            ->generate($frontendUrl);

        // Возвращаем QR-код как изображение для скачивания
        return response($qrCode)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr-code-table-' . $table->table_number . '.png"');
    }

    public function show(Table $table)
    {
        // Проверяем, есть ли уже QR-код
        if (empty($table->qr_code_url) || !Storage::disk('public')->exists(str_replace('/storage/', '', $table->qr_code_url))) {
            // Если QR-кода нет, генерируем его
            $table->generateAndSaveQrCode();
        }
        
        $frontendUrl = $table->getTableUrl();
        

        $activeSession = ActiveSession::where('table_id', $table->id)
            ->where(function ($query) {
                $query->where('created_at', '>', now()->subMinutes(5))
                    ->where('status', 'created')
                    ->orWhere(function ($q) {
                        $q->where('created_at', '>', now()->subMinutes(5))
                            ->where('status', 'paid');
                    });
            })
            ->first();
        $status = $activeSession ? 'inactive' : 'active';
        
        return view('qrcode.show', [
            'table' => $table,
            'qrCodeUrl' => url('/storage/'. $table->qr_code_url),
            'frontendUrl' => $frontendUrl,
            'status' => $status,
        ]);
    }

    public function generate(Table $table)
    {
        // Проверяем, есть ли уже QR-код
        if (empty($table->qr_code_url) || !Storage::disk('public')->exists(str_replace('/storage/', '', $table->qr_code_url))) {
            // Если QR-кода нет, генерируем его
            $table->generateAndSaveQrCode();
        }
        
        // Получаем путь к файлу QR-кода
        $qrCodePath = str_replace('/storage/', '', $table->qr_code_url);
        
        // Возвращаем файл QR-кода
        return response()->file(Storage::disk('public')->path($qrCodePath));
    }
}
