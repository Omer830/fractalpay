<?php

namespace Modules\ExpenseTracker\Repositories;

use Modules\ExpenseTracker\Models\Organisation;




class OrganisationRepository implements \Modules\ExpenseTracker\Interfaces\OrganisationRepositoryInterface
{
    

    public function __construct()
    {
       

    }

    public function create(array $data)
    {
        try {
            // Save Organisation
            return Organisation::firstOrCreate($data);
        } catch (\Illuminate\Database\QueryException $e) {
            // This will show you the exact query and error causing the issue
            throw new \ErrorException('INTERNAL_ERROR', 500, $e);
        }
    }
    public function all()
    {
        return Organisation::all();
    }
}