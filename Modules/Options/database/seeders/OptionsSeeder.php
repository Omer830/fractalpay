<?php

namespace Modules\Options\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Options\Models\Options;
use Illuminate\Support\Collection;

class OptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = Options::allOptions()->toArray();

        Options::truncate();

        $this->verifyData($options);

        // Loop through each category and insert records into the options table
        foreach ($options as $category => $items) {

            foreach ($items as $item) {
                Options::updateOrCreate([
                        'category' => Str::snake($category),
                        'name' => $item['name'],
                        'slug' => $item['slug'],
                    ],
                    [
                        'options_id' => $this->getParentId($item['parent_slug'] ?? null, $item['parent_category'] ?? null)
                    ]
                );
            }
        }
    }

    /**
     * Get the parent ID for the given slug and category.
     */
    private function getParentId(?string $parentSlug, ?string $category): ?int
    {
        if ($parentSlug) {
            $query =  DB::table('options')
                ->where('slug', $parentSlug);

                if($category) {
                    $query = $query->where('category', $category);
                }

                return $query->value('id');
        }

        return null;
    }

    /**
     * Verify that all slugs are unique.
     */
    private function verifyData(array $options): void
    {
        $allSlugs = new Collection();

        foreach ($options as $category => $items) {
            foreach ($items as $item) {
                if ($allSlugs->contains($item['slug'])) {
                    throw new \Exception("Duplicate slug found: " . $item['slug']);
                }
                $allSlugs->push($item['slug']);
            }
        }
    }
}
