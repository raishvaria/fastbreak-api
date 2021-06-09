<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'description' => [
                'nullable',
                'string'
            ],
            'type' => [
                'required',
                'string'
            ],
            'time' => [
                'required',
                'string',
                'max:255'
            ],
            'pickup_location' => [
                'required',
                'string',
                'max:255'
            ],
            'pickup_address' => [
                'required',
                'string',
                'max:255'
            ],
            'address' => [
                'required',
                'string',
                'max:255'
            ],
            'address2' => [
                'nullable',
                'string'
            ],
            'pickup_name' => [
                'nullable',
                'string',
                'max:255'
            ],
            'picture' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,bmp,png',
                'max:20240'
            ],
            'amount_paid' => [
                'required',
                'string',
                'max:255'
            ],
            'phone_number' => [
                'required',
                'integer'
            ],
            'pickup_notes' => [
                'nullable',
                'string'
            ],
            'picture' => [
                'nullable',
                'string'
            ],
            'delivery_notes' => [
                'nullable',
                'string'
            ]
        ];
    }
}
