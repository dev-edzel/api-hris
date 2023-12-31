<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOvertimeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:employees,id',
            'purpose' => 'required',
            'date_start' => 'required|date',
            'start_time' => 'required',
            'date_end' => 'required|date',
            'end_time' => 'required',
            'duration' => 'required|integer',
            'status' => 'in:pending,approved,declined',
        ];
    }
}
