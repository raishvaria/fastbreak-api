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
            'first_name' => [
                'required',
                'string',
                'max:255'
            ],
            'last_name' => [
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
            'zip' => [
                'required',
                'string',
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
            'model' => [
                'required',
                'string',
                'max:50'
            ],
            'date_of_birth' => [
                'required',
                'date_format:m-d-Y',
                'max:10'
            ],
            'address' => [
                'required',
                'string',
                'max:100'
            ],
            'latitude' => [
                'required',
            ],
            'longitude' => [
                'required'
            ],
            'phone_number' => [
                'required',
                'integer'
            ],
            'paypal' => [
                'max:150'
            ],
            'venmo' => [
                'max:150'
            ],
        ];
    }

    public function getUserPayload()
    {
        return collect($this->validated())
            ->only([
                'email',
                'password',
                'drivers_license',
                'year',
                'make',
                'model',
                'date_of_birth',
                'address',
                'latitude',
                'longitude',
                'phone_number',
                'paypal',
                'venmo',
                'zip'
            ])
            ->merge([
                'name' => $this->first_name . ' ' . $this->last_name
            ])
            ->toArray();
    }
}
