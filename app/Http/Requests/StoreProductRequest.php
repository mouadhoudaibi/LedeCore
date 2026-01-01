<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'description' => ['nullable', 'string', 'max:2000'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku', 'regex:/^[A-Z0-9\-]+$/'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'promo_price' => ['nullable', 'numeric', 'min:0.01', 'max:999999.99', 'lt:price'],
            'stock_quantity' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:products,slug', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => __('admin.category_required'),
            'category_id.exists' => __('admin.category_not_found'),
            'name.required' => __('admin.product_name_required'),
            'name.min' => __('admin.product_name_min'),
            'name.max' => __('admin.product_name_max'),
            'sku.required' => __('admin.sku_required'),
            'sku.unique' => __('admin.sku_already_exists'),
            'sku.regex' => __('admin.sku_invalid_format'),
            'price.required' => __('admin.price_required'),
            'price.min' => __('admin.price_min'),
            'price.max' => __('admin.price_max'),
            'promo_price.lt' => __('admin.promo_price_must_be_less'),
            'image.max' => __('admin.image_too_large'),
            'image.mimes' => __('admin.image_invalid_format'),
        ];
    }
}
