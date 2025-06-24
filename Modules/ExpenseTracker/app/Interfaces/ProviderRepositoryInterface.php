<?php

namespace Modules\ExpenseTracker\Interfaces;
use Modules\Options\Models\Options;
use Illuminate\Database\Eloquent\Collection;

interface ProviderRepositoryInterface
{
    public function create(array $data); 
    public function findByProviderNumber($providerNumber);
    public function findProviderWithOrganisation($providerNumber);




}