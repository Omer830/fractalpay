<?php

namespace Modules\Options\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Options\Models\Options;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keys = config('attributes_config.keys');
        $values = config('attributes_config');

        foreach ($keys as $type => $catVal) {
            if (isset($values[$type])) {
                foreach ($values[$type] as $category => $attributes) {

                    foreach ($attributes as $attributeName => $attributeValues) {

                        $option = Options::findBySlug($attributeName, $category);

                        if(!$option) {
                            continue;
                        }
                        foreach ($attributeValues as $key => $value) {

                            $option->attribute()->updateOrCreate(['key'   =>  $key],
                                ['value' =>  $value]
                            );

                        }
                    }
                }
            }
        }
    }

}
