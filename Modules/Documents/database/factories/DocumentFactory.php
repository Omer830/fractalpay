<?php

namespace Modules\Documents\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
//    protected $model = \App\Models\Documents\app\Models\Document::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
//            'document_type_id' => DocumentType::inRandomOrder()->first()->id,
            'points' => $this->faker->numberBetween(1, 20),
            'sides_required' => $this->faker->randomElement(['one', 'two', 'three']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

