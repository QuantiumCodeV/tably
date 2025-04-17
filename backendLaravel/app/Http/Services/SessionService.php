<?php

namespace App\Services;

use App\Models\ActiveSession;
use Illuminate\Support\Str;

class SessionService
{
    public function createSession(int $tableId): ActiveSession
    {
        $lastSession = ActiveSession::where('table_id', $tableId)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastSession || $lastSession->status === 'payed') {
            return ActiveSession::create([
                'table_id' => $tableId,
                'session_token' => Str::uuid(),
                'status' => 'active',
                'started_at' => now(),
                'expires_at' => now()->addHours(2),
            ]);
        }

        return $lastSession;
    }
}

