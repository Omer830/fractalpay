<?php

namespace Modules\Options\Interfaces;

use Illuminate\Support\Collection;

interface OptionListRepositoryInterface
{
    public function getCategories() : Collection;

    public function getCategoryList() : array;
}