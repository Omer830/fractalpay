<?php

namespace Modules\Documents\database\seeders;
use Illuminate\Database\Seeder;

class DocumentsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $this->call([
            CountrySeeder::class,
            DocumentTypeSeeder::class,
            DocumentSeeder::class
        ]);
    }
}
