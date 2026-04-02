<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFinancialRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'type'        => 'sometimes|in:income,expense',
            'amount'      => 'sometimes|numeric|min:0',
            'category'    => 'sometimes|string|max:100',
            'date'        => 'sometimes|date',
            'status'      => 'sometimes|in:pending,approved,rejected',
        ];
    }
}
