<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverRegistrationRequest extends FormRequest
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
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
            'drivers_license' => [
                'required',
                'string',
                'max:50'
            ],
            'year' => [
                'required',
                'integer',
                'date_format:Y'
            ],
            'make' => [
                'required',
                'string',
                'max:50'
            ],
            'model' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['required', 'date_format:m-d-Y', 'max:10'],
            'address' => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'integer'],
            'paypal' => ['max:150'],
            'venmo' => ['max:150'],
        ];
    }
}
