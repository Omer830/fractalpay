<?php

namespace Modules\ExpenseTracker\Repositories;


use Modules\ExpenseTracker\Models\Providers;


class ProviderRepository implements \Modules\ExpenseTracker\Interfaces\ProviderRepositoryInterface
{


    public function __construct()
    {
        

    }
  
    public function create(array $data)
    {
        return Providers::create($data);
    }
    public function findByProviderNumber($providerNumber)
    {
       
        return Providers::where('provider_number', $providerNumber)->first();
    }
    public function findProviderWithOrganisation($providerNumber)
    {
        return Providers::where('provider_number', $providerNumber)
            ->with('organisation')
            ->first();
    }
}