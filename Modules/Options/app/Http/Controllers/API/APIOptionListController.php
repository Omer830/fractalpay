<?php

namespace Modules\Options\Http\Controllers\API;

use Modules\Options\Contracts\OptionListControllerInterface;
use Modules\Options\Services\OptionListService;
use Modules\Options\Http\Controllers\OptionsController;
use Illuminate\Http\Request;
use Modules\Options\Transformers\CategoryList;
use Modules\Options\Transformers\CategoryStructure;
use Modules\Options\Transformers\OptionsList;

class APIOptionListController extends OptionsController implements OptionListControllerInterface
{

    public function __construct(private OptionListService $OptionListService)
    {
        
    }

    public function getCategories(Request $request)
    {
        return CategoryStructure::collection($this->OptionListService->getCategoryList());
    }


    public function getList(Request $request)
    {
        return new CategoryList($this->OptionListService->getOptionsList($request->all()));
    }
}