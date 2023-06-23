<?php

namespace Database\Factories;

use App\Models\Preference;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreferenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Preference::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'scout_id' => $this->faker->numberBetween(1, 10),
            'program_id' => $this->faker->numberBetween(1, 8),
            'rank' => $this->faker->numberBetween(1, 6),
            'satisfied' => false,
        ];
    }
}
