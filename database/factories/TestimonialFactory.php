<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name'   => $this->faker->firstName() . ' ' . $this->faker->lastName(),
            'photo'       => 'storage/testimonials/person_' . random_int(1, 6) . '-min.jpg',
            'company'     => $this->faker->jobTitle() . ', ' . $this->faker->company(),
            'rating'      => random_int(1, 5),
            'testimonial' => $this->faker->words(30, asText: true),
        ];
    }
}
