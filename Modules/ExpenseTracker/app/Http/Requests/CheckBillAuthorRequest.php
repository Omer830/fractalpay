<?php

namespace Modules\ExpenseTracker\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Modules\ExpenseTracker\Models\Bill;
use Illuminate\Foundation\Http\FormRequest;
use Modules\ExpenseTracker\Models\BillContributor;

class CheckBillAuthorRequest  extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'bill_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    if (!$this->checkBillOwnership($value)) {
                        $fail('The selected bill does not belong to the authenticated user or contributor.');
                    }
                },
            ],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function checkBillOwnership($billId): bool
    {
       
        $isOwner = Bill::where('id', $billId)
            ->where('user_id', Auth::id())
            ->exists();

       
        $isContributor = BillContributor::where('bill_id', $billId)
            ->where('contributor_id', Auth::id())
            ->exists();

 
        return $isOwner || $isContributor;
    }
    public function authorize(): bool
    {
        return true;
    }
}
