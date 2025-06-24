<?php

namespace Modules\ExpenseTracker\Repositories;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\ExpenseTracker\DTO\UserDTO;
use Modules\ExpenseTracker\Interfaces\VisitRepositoryInterface;
use Modules\Options\Models\Options;
use Modules\Options\Services\OptionListService;

class ExpensesRepository implements \Modules\ExpenseTracker\Interfaces\ExpensesRepositoryInterface
{
    // Add your methods here
    public $optionListService;

    public function __construct(OptionListService $optionListService, public VisitRepositoryInterface $VisitRepository)
    {
        $this->optionListService = $optionListService;
    }

    public function fetchStats(UserDTO $userDTO) : Collection
    {
        $user = $userDTO->user();

        $bills = $user->bills()->get();

        $now = \Carbon\Carbon::now();
        $endOfWeek = $now->endOfWeek();
        $categories = $bills->pluck('category')->unique();

        // Calculate the various bill totals
        return collect([

            'total' => $bills->sum('balance'),

            // Bills due this week
            'due_this_week' => $bills->filter(function ($bill) use ($now, $endOfWeek) {
                return $bill->due_date >= $now && $bill->due_date <= $endOfWeek;
            })->sum('balance'),

            // Overdue bills
            'over_due_bills' => $bills->filter(function ($bill) use ($now) {
                return $bill->due_date < $now; // Bills past due
            })->sum('balance'),

            'categories' => $categories->map(function ($category) use ($bills) {
                return [
                    'name' => $category,
                    'value' => $bills->where('category', $category)->sum('balance')
                ];
            })->values(), // Convert collection to array
        ]);

    }

    public function fetchVisitBills(UserDTO $userDTO, array $data) : Collection
    {
        $visitBills = collect();
        if( $data['isOwner'] )
        {
           
            $ownerData = $this->fetchVisitBillsOwner($userDTO, $data);
            $visitBills->put('owner', $ownerData);

        }
        if( $data['isContributor'] )
        {
           
            $contributorData = $this->fetchVisitBillsContributor($userDTO, $data);
            $visitBills->put('contributor', $contributorData); 
            // dd( $this->fetchVisitBillsContributor($userDTO,  $data));
        //    return $this->fetchVisitBillsContributor($userDTO,  $data);

        }
        // dd($visitBills);
            return $visitBills;

    }
    
    private function fetchVisitBillsOwner(UserDTO $userDTO, array $data) : Collection
    {
        $visits     =   $data['visits'] ?? null;

        $bills      =   $data['bills'] ?? null;
        
        $user       =   $userDTO->user();

        $visitBills =   $user->visits()   
            ->when($visits, function ($query) use ($visits) {
                return $query->whereIn('id', $visits);
            })
            ->when($bills, function ($query) use ($bills) {
                return $query->whereHas('bills', function ($query) use ($bills) {
                    return $query->whereIn('id', $bills);
                });
            })
            ->with('bills', function ($query) use ($bills) {
                $query->when($bills, function ($query) use ($bills) {
                    return $query->whereIn('id', $bills);
                });
            })
            ->get();

        return $visitBills->groupBy('organisation')->map(function ($visits, $organisation) {

            return [
                'organisation_name' => $organisation,
                'visits'            => $visits
            ];

        })->values();
    }

    private function fetchVisitBillsContributor(UserDTO $userDTO, array $data) : Collection
    {
        
        $bills    =  $data['bills'] ?? null;

        $user     =  $userDTO->user();
        
        $visitIds =  $user->ContributorBills()
            ->when($bills, function ($query) use ($bills) {
                return $query->whereIn('bills.id', $bills); 
            })
            ->pluck('visit_id')->toArray();

     

           
        $contributorVisits = $this->VisitRepository->get($visitIds)
        ->load(['bills' => function ($query) use ($bills) {
            return $query->when($bills, function ($query) use ($bills) {
                return $query->whereIn('id', $bills);
            });
        }]);
            

        
            return $contributorVisits->groupBy('organisation')->map(function ($visits, $organisation) {
                return [
                    'organisation_name' => $organisation,
                    'visits' => $visits,
                ];
            })->values();

    }
    
}