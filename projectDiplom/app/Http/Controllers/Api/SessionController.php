<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActiveSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SessionController extends Controller
{
    public function create(Request $request)
    {
        $tableId = $request->input('table_id');

        $validator = Validator::make($request->all(), [
            'table_id' => 'required|exists:tables,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Получаем последнюю сессию для данного стола
        $lastSession = ActiveSession::where('table_id', $tableId)
            ->orderBy('created_at', 'desc')
            ->first();

        // Проверяем условия для создания новой сессии
        if ($lastSession) {
            // Если статус последней сессии "payed", то можно создать новую сессию
            if ($lastSession->status === 'payed') {
                // Стол свободен, можно создать новую сессию
            } 
            // Если статус не "payed" и сессия создана менее 10 минут назад
            elseif (Carbon::parse($lastSession->created_at)->diffInMinutes(Carbon::now()) < 10) {
                return response()->json(['error' => 'Стол занят. Пожалуйста, попробуйте позже.'], 400);
            }
            // Если статус не "payed", но сессия создана более 10 минут назад
            // то можно создать новую сессию
        }

        // Создаем новую сессию
        $session = ActiveSession::create([
            'table_id' => $tableId,
            'status' => 'active'
        ]);

        return response()->json(['session_id' => $session->id]);
    }   

    public function getSession($sessionId)
    {
        $session = ActiveSession::find($sessionId);

        return response()->json($session);
    }

    public function updateStatus(Request $request, $sessionId)
    {
        $session = ActiveSession::find($sessionId);
        $session->status = $request->input('status');
        $session->save();

        return response()->json($session);
    }
}


