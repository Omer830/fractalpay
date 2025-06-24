<?php

namespace Modules\Options\Services;

use App\Exceptions\ErrorException;
use Modules\Options\Interfaces\OptionListRepositoryInterface;

class OptionListService
{

    public function __construct(private OptionListRepositoryInterface $OptionListRepository)
    {

    }

    public function getCategoryList()
    {
        try {

            return $this->OptionListRepository->getCategories();

        }
        catch (\Exception $e){
            throw new ErrorException('UN_SUCCESSFUL', previous: $e);
        }
    }

    public function getOptionsList($data)
    {

        try {
            // Define all possible categories
            $categories = $this->OptionListRepository->getCategoryList();

            // Get requested categories from the query parameters, or default to all categories
            $requestedCategories = $data['categories'] ? explode(',', $data['categories']) : $categories;
            $cleanRequest = array_intersect($categories, $requestedCategories);

            $parent = $data['parent'] ?? null;
            if($parent && !is_string($parent)) {
                throw new ErrorException('ONLY_STRING_ALLOWED');
            }

            $with = $data['with'] ?? null;

            return $this->OptionListRepository->get($cleanRequest, $parent, $with);

        } catch (\Exception $e) {
            throw new ErrorException('UN_SUCCESSFUL', previous: $e);
        }


    }
}