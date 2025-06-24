<?php

namespace Modules\ExpenseTracker\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\ExpenseTracker\Http\Resources\ProviderResource;
use Modules\ExpenseTracker\Http\Resources\OrganisationResource;
use Modules\ExpenseTracker\Interfaces\ProviderRepositoryInterface;
use Modules\ExpenseTracker\Interfaces\OrganisationRepositoryInterface;


class ProviderService
{
    const Page_Number = 1;
    const Page_Size = 20;
    const Search_Type = 'doctor';

    private mixed $apiURL;
    public function __construct(private ProviderRepositoryInterface $ProviderRepository,private OrganisationRepositoryInterface $OrganisationRepository)
    {
        $this->apiURL = config('services.provider.endpoint');
    }
    public function getProviderDetails($providerNumber)
    {
        try {
            
            $providers=$this->ProviderRepository->findByProviderNumber($providerNumber);
            if(!$providers)
            {
                $providerCreate= $this->callApi($providerNumber);
                return $this->ProviderRepository->findProviderWithOrganisation($providerNumber);
            }else{
                return $this->ProviderRepository->findProviderWithOrganisation($providerNumber);
            }
        } catch (\Exception $e) {
            throw new \ErrorException('INTERNAL_ERROR', 500, $e);
        }
    }

    private function callApi($providerNumber,$pageNumber=self::Page_Number,$pageSize=self::Page_Size,$searchType=self::Search_Type)
    {
        try {
            $query=$this->graphQLQuery();
            $variables = [
                'input' => [
                    'pageNumber' => $pageNumber,
                    'pageSize' => $pageSize,
                    'searchType' => $searchType,
                    'providerNumber' => $providerNumber
                ]
            ];
           
            $response = Http::post($this->apiURL, [
                'query' => $query,  
                'variables' => $variables,  
            ]);
             
        if ($response->failed()) {
        
           
            throw new \Exception('API request failed');
        }
        
           return $this->handleApiResponse($response->json());
       
        } catch (\Exception $e) {
            throw new \ErrorException('INTERNAL_ERROR', 500, $e);
        }
    }
    private function handleApiResponse($response)
    {
        $providers = $response['data']['findProviders']['results'];

        $savedResources = [];

        foreach ($providers as $providerData) {
            // Organisation Data
            $organisationData = new OrganisationResource($providerData['address']);
            
            // Convert the resource to an array
            $transformedOrganisationData = $organisationData->toArray(null);
            
            // Save Organisation
            $organisation = $this->OrganisationRepository->create($transformedOrganisationData);

            // Provider Data
            $providerResource = new ProviderResource($providerData);
            $transformedProvidersData = $providerResource->toArray(null);

            // Assign the organisation_id after saving the organisation
            $transformedProvidersData['organisation_id'] = $organisation->id;

            // Save Provider
            $provider = $this->ProviderRepository->create($transformedProvidersData);

            // Wrap the saved organisation and provider into their respective resources
            $savedOrganisationResource = new OrganisationResource($organisation);
            $savedProviderResource = new ProviderResource($provider);

            // Collect saved resources in the array
            $savedResources[] = [
                'organisation' => $savedOrganisationResource->toArray(null),
                'provider' => $savedProviderResource->toArray(null),
            ];
        }
        return $savedResources;
    }
    
    public function storeBill(array $data)
    {
        try {
            // dd($data);
            
            return '';
        } catch (\Exception $e) {
            throw new \ErrorException('INTERNAL_ERROR', 500, $e);
        }
    }
    private function graphQLQuery()
    {
        $query = <<<GRAPHQL
        query omsFindProviders(\$input: FindProvidersInput) {
            findProviders(input: \$input) {
                count
                pageNumber
                pageSize
                results {
                    ... on FindProvidersResults {
                        providerNumber
                        name
                        noGapDental
                        phone
                        address {
                            line1
                            line2
                            suburb
                            postcode
                            state
                            __typename
                        }
                        __typename
                    }
                    ... on FindHospitalsResults {
                        providerNumber
                        name
                        contractType
                        address {
                            line1
                            line2
                            suburb
                            postcode
                            state
                            __typename
                        }
                        __typename
                    }
                    ... on FindDoctorsResults {
                        providerNumber
                        name
                        phone
                        address {
                            line1
                            line2
                            suburb
                            postcode
                            state
                            __typename
                        }
                        __typename
                    }
                    __typename
                }
                __typename
            }
        }
        GRAPHQL;
        return $query;
    }
}