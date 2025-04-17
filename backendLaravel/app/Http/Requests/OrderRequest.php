<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // или твоя авторизация
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'session_id' => 'required|exists:active_sessions,id',
            'payment_method' => 'required|in:card,cash,sbp',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ];
    }
}
