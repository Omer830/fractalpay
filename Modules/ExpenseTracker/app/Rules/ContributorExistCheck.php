<?php

namespace Modules\ExpenseTracker\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContributorExistCheck implements ValidationRule
{

    protected $billId;
    protected $contributorsIds;

    public function __construct($billId, $contributorsIds)
    {
        $this->billId = $billId;
        $this->contributorsIds = $contributorsIds;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if any contributors already exist for the given bill_id and contributor_ids
        $existingContributors = DB::table('bill_contributor')
            ->where('bill_id', $this->billId)
            ->whereIn('contributor_id', $this->contributorsIds)
            ->exists();

        if ($existingContributors) {
            $fail('Some contributors have already been assigned to this bill.');
        }
    }
}
