<?php

namespace Modules\Options\Http\Controllers\Web;

use Modules\Options\Contracts\OptionListControllerInterface;
use Modules\Options\Services\OptionListService;
use Modules\Options\Http\Controllers\OptionsController;

class WebOptionListController extends OptionsController implements OptionListControllerInterface
{

    public function __construct(private OptionListService $OptionListService)
    {
        
    }

    // Add your methods here
}