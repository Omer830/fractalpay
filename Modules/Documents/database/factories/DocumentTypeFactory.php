<?php

namespace Modules\Documents\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
//    protected $model = \App\Models\Documents\app\Models\DocumentType::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
        ];
    }
}

