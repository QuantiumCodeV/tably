<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActiveSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Http\Requests\SessionCreateRequest;
use App\Http\Resources\SessionResource;
use App\Services\SessionService;

class SessionController extends Controller
{
    public function __construct(
        protected SessionService $sessionService
    ) {}

    public function create(SessionCreateRequest $request)
    {
        $session = $this->sessionService->createSession($request->validated()['table_id']);

        return new SessionResource($session);
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
