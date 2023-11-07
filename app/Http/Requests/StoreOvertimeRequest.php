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
            'name' => 'required',
            'purpose' => 'required',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
            'end_date' => 'required|date',
            'duration' => 'required|integer',
        ];
    }
}
