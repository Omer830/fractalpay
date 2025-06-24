<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AwsCustomAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'attributes' => 'required|array|min:1',
            'attributes.*.name' => 'required|string|max:50|regex:/^[a-zA-Z_]+$/',
            'attributes.*.type' => 'required|string|in:String,Number,DateTime,Boolean',
            'attributes.*.mutable' => 'sometimes|boolean',
            'attributes.*.max_length' => 'required_if:attributes.*.type,String|integer|min:1|max:2048',
            'attributes.*.min_length' => 'required_if:attributes.*.type,String|integer|min:1|lte:attributes.*.max_length',
            'attributes.*.required' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'attributes.*.name.regex' => 'Attribute name can only contain letters and underscores',
            'attributes.*.type.in' => 'Attribute type must be String, Number, DateTime, or Boolean',
            'max_length.required_if' => 'Max length is required for String type',
            'min_length.required_if' => 'Min length is required for String type',
        ];
    }
}