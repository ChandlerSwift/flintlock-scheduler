<?php

namespace Database\Factories;

use App\Models\Scout;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScoutFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Scout::class;

    private $sites = [
        'Voyageur: Fort Francis',
        'Voyageur: Vermilion',
        'Voyageur: Duluth',
        'Voyageur: Superior',
        'Voyageur: Grand Portage',
        'Voyageur: Quetico',
        'Voyageur: Grand Marais',
        'Ten Chiefs: Taskalusa',
        'Ten Chiefs: Powhatan',
        'Ten Chiefs: Roman Nose',
        'Ten Chiefs: Massasoit',
        'Ten Chiefs: Red Cloud',
        'Buckskin: Tyler',
        'Buckskin: Dickson',
        'Buckskin: Bearde',
        'Buckskin: Fitzpatrick',
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'rank' => $this->faker->numberBetween(1, 6), // TODO: inclusive
            'age' => $this->faker->numberBetween(10, 17), // TODO: "
            'grade' => $this->faker->numberBetween(6, 12),
            'years_at_camp' => $this->faker->numberBetween(1, 6),
            'unit' => $this->faker->numerify('####'),
            'site' => $this->faker->randomElement($this->sites),
        ];
    }
}
