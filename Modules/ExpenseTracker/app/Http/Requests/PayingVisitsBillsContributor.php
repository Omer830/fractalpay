<?php

namespace Modules\ExpenseTracker\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Modules\ExpenseTracker\Models\Bill;
use Illuminate\Foundation\Http\FormRequest;
use Modules\ExpenseTracker\Models\BillContributor;
use Modules\ExpenseTracker\Http\Requests\CheckBillAuthorRequest;

class PayingVisitsBillsContributor extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'visits'   => [Rule::requiredIf(!$this->bills), 'array'],
            'visits.*' => ['integer', Rule::exists('visits', 'id')->where('user_id', Auth::id())],
            'bills'    => [Rule::requiredIf(!$this->visits), 'array'],
            'bills.*'  => ['integer', function ($attribute, $value, $fail) {
                if (!$this->checkBillOwnership($value)) {
                    $fail('The selected bill does not belong to the authenticated user or contributor.');
                }
            }],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Check ownership of an individual bill.
     */
    private function checkBillOwnership($billId): bool
    {
        $ownership = $this->determineOwnership();
        return $ownership['isOwner'] || $ownership['isContributor'];
    }

    /**
     * Determine ownership across all bills in the request.
     */
    private function determineOwnership(): array
    {
        $bills = $this->input('bills', []);

        if (empty($bills)) {
            return ['isOwner' => false, 'isContributor' => false];
        }

        // Check if the user owns at least one bill
        $isOwner = Bill::whereIn('id', $bills)
            ->where('user_id', Auth::id())
            ->exists();

        // Check if the user is a contributor to at least one bill
        $isContributor = BillContributor::whereIn('bill_id', $bills)
            ->where('contributor_id', Auth::id())
            ->exists();

        return [
            'isOwner' => $isOwner,
            'isContributor' => $isContributor,
        ];
    }

    /**
     * Override the validated method to include ownership status.
     */
    public function validated($key = null, $default = null): array
    {
        $validatedData = parent::validated($key, $default);

        // Add ownership status to the validated data
        $ownershipStatus = $this->determineOwnership();
        $validatedData['isOwner'] = $ownershipStatus['isOwner'];
        $validatedData['isContributor'] = $ownershipStatus['isContributor'];

        return $validatedData;
    }
}
