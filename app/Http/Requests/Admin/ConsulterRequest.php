<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ConsulterRequest extends FormRequest
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
        $rules = [
            'first_name' => ['required', 'max:120'],
            'last_name' => ['required', 'max:120'],
            'about_me' => ['required', 'min:10'],
            'experience' => ['required', 'min:3'],
            'professional_title' => ['required', 'min:3'],
            'fee' => ['required','min:0', 'numeric'],
            'image' => 'mimes:jpeg,png,jpg,webp|file'
        ];

        // Check if this is an update request by checking if $this->id is present
        if ($this->id) {
            $rules['password'] = ['nullable'];
        } else {
            $rules['password'] = ['required'];
        }

        // Apply additional required rules for ADMIN role
        if (auth()->user()->role === 'USER_ROLE_ADMIN') {
            $rules['email'] = ['required', 'email', 'unique:users,email,' . $this->id];
            $rules['mobile'] = ['required', 'min:6'];
            $rules['gender'] = ['required'];
        }

        return $rules;
    }
}
