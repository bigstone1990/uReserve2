<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $availableHour = fake()->numberBetween(10, 18);
        // $minutes = [0, 30];
        // $mKey = array_rand($minutes, 1);
        $minutesRev = fake()->randomElement([0, 30]);
        $addHour = fake()->numberBetween(1, 3);

        $dummyDate = fake()->dateTimeThisMonth();
        $startDate = $dummyDate->setTime($availableHour, $minutesRev);
        $clone = clone $startDate;
        $endDate = $clone->modify('+'.$addHour.'hour');

        return [
            'name' => fake()->name(),
            'information' => fake()->realText(),
            'max_people' => fake()->numberBetween(1, 20),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_visible' => fake()->boolean(),
        ];
    }
}
