<?php

namespace Modules\ExpenseTracker\Interfaces;
use Modules\Options\Models\Options;
use Illuminate\Database\Eloquent\Collection;

interface OrganisationRepositoryInterface
{
    public function create(array $data); 
    public function all();




}