<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer.name'         => ['required', 'string', 'max:255'],
            'customer.email'        => ['required', 'email', 'max:255'],
            'customer.phone_number' => ['required', 'string', 'max:30'],
            'customer.address'      => ['required', 'string', 'max:255'],
            'items'                 => ['required', 'array', 'min:1'],
            'items.*.product_id'    => ['required', 'integer', 'exists:products,id'],
            'items.*.size_id'       => ['required', 'integer', 'exists:sizes,id'],
            'items.*.color_id'      => ['required', 'integer', 'exists:colors,id'],
            'items.*.quantity'      => ['required', 'integer', 'min:1'],
            'payment_method'        => ['required', 'string', 'in:cod'],
        ];
    }
}
