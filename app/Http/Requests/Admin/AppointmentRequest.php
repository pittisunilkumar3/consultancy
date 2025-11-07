<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // Basic rules
        $rules = [
            'date' => [
                'required',
                'date',
                $this->user()->role === USER_ROLE_ADMIN ? 'nullable' : 'after_or_equal:' . now()->toDateString(),
            ],
            'consultation_slot' => 'required',
            'consultation_type' => 'required',
            'consultant' => 'required',
            'student' => 'required',
            'gateway' => 'required',
            'gateway_currency' => 'required',
        ];

        // Get the consultant based on the provided ID
        $consultant = User::where(['role' => USER_ROLE_CONSULTANT, 'status' => STATUS_ACTIVE, 'id' => request()->get('consultant')])->first();

        // If consultant exists and fee is less than 0, add validation rules for gateway and currency
        if ($consultant && $consultant->fee < 1) {
            $rules['gateway'] = 'nullable';
            $rules['gateway_currency'] = 'nullable';
        }

        return $rules;
    }
}
