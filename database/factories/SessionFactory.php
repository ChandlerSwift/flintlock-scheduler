<?php

namespace Database\Factories;

use App\Models\Session;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Session::class;

    private $timeSlots = [
        '2021-06-28 09:00:00', //Mon
        '2021-06-28 13:00:00',
        '2021-06-28 17:00:00',
        '2021-06-29 09:00:00', //Tue
        '2021-06-29 13:00:00',
        '2021-06-29 17:00:00',
        '2021-06-30 09:00:00', //Wed
        '2021-06-30 13:00:00',
        '2021-06-30 17:00:00',
        '2021-07-01 09:00:00', //Thu
        '2021-07-01 13:00:00',
        '2021-07-01 17:00:00',
        '2021-07-02 09:00:00', //Fri
        '2021-07-02 13:00:00',
        //No overnight Friday
    ];

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'program_id' => $this->faker->numberBetween(1, 8),
            'session_time' => $this->faker->randomElement($this->timeSlots),
        ];
    }
}
