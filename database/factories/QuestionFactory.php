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

    public function published(): static
    {
        return $this->state(fn () => [
            'draft' => false,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'draft' => true,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn () => [
            'deleted_at' => fake()->dateTime,
        ]);
    }
}
