<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'articleTitle' => $this->faker->word, 
            'date' => $this->faker->date,
            'summary' => $this->faker->paragraph, 
            'text' => $this->faker->paragraphs, 
            'imageItemGuid' => $this->faker->word, 
            'linkTitle' => $this->faker->sentence, 
            'linkItemGuid' => $this->faker->words
        ];
    }
}
