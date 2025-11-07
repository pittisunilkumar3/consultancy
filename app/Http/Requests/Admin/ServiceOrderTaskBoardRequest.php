<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ServiceOrderTaskBoardRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'task_name' => 'bail|required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'task_name.required' => __('The name field is required.'),
        ];
    }

}
