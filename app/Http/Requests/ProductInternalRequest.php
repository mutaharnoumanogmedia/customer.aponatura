<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductInternalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('product_internal')?->id;

        return [
            'sku'                  => ['nullable', 'string', 'max:50', Rule::unique('product_internals', 'sku')->ignore($id)],
            'internal_code'        => ['nullable', 'string', 'max:100'],
            'batch_number'         => ['nullable', 'string', 'max:50'],
            'location'             => ['nullable', 'string', 'max:100'],
            'stock_quantity'       => ['nullable', 'integer', 'min:0'],
            'min_stock_threshold'  => ['nullable', 'integer', 'min:0'],
            'max_stock_threshold'  => ['nullable', 'integer', 'min:0'],
            'unit'                 => ['nullable', 'string', 'max:20'],
            'price'                => ['nullable', 'numeric'],
            'wholesale_price'      => ['nullable', 'numeric'],
            'retail_price'         => ['nullable', 'numeric'],
            'product_type'         => ['required', Rule::in(['physical', 'digital', 'service'])],
            'file_path'            => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,svg,webp,pdf,zip,doc,docx', 'max:4096'],
            'image_path'           => ['nullable', 'image', 'max:4096'],
            'license_key'          => ['nullable', 'string', 'max:255'],
            'max_downloads'        => ['nullable', 'integer', 'min:0'],
            'access_expires_at'    => ['nullable', 'date'],
            'is_active'            => ['boolean'],
            'is_discontinued'      => ['boolean'],
            'status'               => ['required', Rule::in(['in_stock', 'out_of_stock', 'archived'])],
            'received_at'          => ['nullable', 'date'],
            'expires_at'           => ['nullable', 'date'],
            'notes'                => ['nullable', 'string'],
            'attributes'           => ['nullable', 'string'],
        ];
    }
}
