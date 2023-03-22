<?php

namespace Database\Factories;

use App\Models\Agent;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $agents = collect(Agent::all()->modelKeys());

        return [
            'agent_id'    => $agents->random(),
            'price'       => $this->faker->randomNumber(),
            'address'     => $this->faker->address(),
            'country'     => $this->faker->country(),
            'beds'        => $this->faker->numberBetween(1, 3),
            'baths'       => $this->faker->numberBetween(1, 3),
            'description' => $this->faker->text(),
            'is_popular'  => $this->faker->boolean(),
            'is_featured' => $this->faker->boolean(),
        ];
    }
}
