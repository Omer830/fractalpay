<?php

namespace Modules\Documents\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Documents\Enums\DocumentSides;
use Modules\Options\Enums\Categories;
use Modules\Options\Models\Options;
use Modules\Options\Rules\VerifyOptions;

class UserKycDocuments extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file_type' => ['required', new VerifyOptions(category: Categories::DOCUMENTS->value, slug: $this->file_type)],
            'file'  =>  ['required', 'mimes:jpeg,png,jpg,pdf', 'max:2048'],
            'side'  =>  [ Rule::requiredIf($this->checkRequired()), Rule::enum(DocumentSides::class)]
        ];
    }


    public function checkRequired() : bool
    {
        $numberOfFiles = Options::findAttributeByName($this->file_type, Categories::DOCUMENTS->value, 'number_of_files', 1);
        return ($numberOfFiles > 1);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
