<?php

namespace Modules\ExpenseTracker\Http\Requests;


use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\ExpenseTracker\Rules\ContributorExistCheck;

class AssignContributors extends FormRequest
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
        $billId = $this->input('bill_id');

        return [

            'bill_id'           =>  ['required', 'integer', Rule::exists('bills', 'id')],
            
            'contributorsIds.*' =>  ['exists:users,id'],

            'contributorsIds' => [new ContributorExistCheck($billId, $this->input('contributorsIds'))], 

        ];
    }
    
}
