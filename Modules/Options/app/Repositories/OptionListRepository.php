<?php

namespace Modules\Options\Repositories;


use Modules\Options\Models\Options;

class OptionListRepository implements \Modules\Options\Interfaces\OptionListRepositoryInterface
{

    public function __construct(private Options $option)
    {
    }

    public function getCategories(): \Illuminate\Support\Collection
    {
        return $this->option
                ->withCount('children')
                ->get()->unique('category');
    }

    public function getCategoryList() : array
    {
        return $this->getCategories()->pluck('category')->toArray();
    }

    public function get($categories = [], string|null $parent = null, $with = null)
    {

        $result = [];
        $query = $this->option->query();

        if($with && is_string($with)) {
            $query->with([$with]);
        }

        if($categories && !empty($categories)) {

            $query->whereIn('category', $categories)
                ->with('children', function($q) use($categories) {
                    $q->whereIn('category', $categories);
                });

            if ($parent) {
                $query->whereHas('parent', function ($q) use ($parent) {
                    $q->where('slug', $parent);
                });
            }

            $result = $query->get();


        }

        return $result;

    }

}