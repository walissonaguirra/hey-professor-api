<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'question' => fake()->sentence() . '?',
            'draft'    => true,
            'user_id'  => User::factory(),
        ];
    }

    public function published(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'draft' => false,
        ]);
    }

    public function draft(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'draft' => true,
        ]);
    }
}
