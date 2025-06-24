<?php

namespace Modules\Options\Database\Seeders;

use Illuminate\Database\Seeder;

class OptionsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            OptionsSeeder::class,
            AttributeSeeder::class
        ]);
    }
}
