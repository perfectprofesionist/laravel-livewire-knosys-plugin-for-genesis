<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'article_id' => '5105b1f7-87d1-ec11-a846-000d3ae106ec',
            'user_id' => $this->faker->uuid,
            'rating_star' => rand(0,5),
            'rating_text' => $this->faker->sentence
        ];
    }
}
