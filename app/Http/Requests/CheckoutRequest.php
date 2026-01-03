<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
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
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => [
                'required',
                'string',
                'regex:/^(06|07)[0-9]{8}$/',
            ],
            'city' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'min:5'],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => __('checkout.validation.name_required'),
            'customer_name.max' => __('checkout.validation.name_max'),
            'customer_email.required' => __('checkout.validation.email_required'),
            'customer_email.email' => __('checkout.validation.email_invalid'),
            'customer_email.max' => __('checkout.validation.email_max'),
            'customer_phone.required' => __('checkout.validation.phone_required'),
            'customer_phone.regex' => __('checkout.validation.phone_format'),
            'city.required' => __('checkout.validation.city_required'),
            'city.max' => __('checkout.validation.city_max'),
            'address.required' => __('checkout.validation.address_required'),
            'address.min' => __('checkout.validation.address_min'),
        ];
    }
}
