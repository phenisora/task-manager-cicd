<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3), // string, max 255, required [cite: 21]
            'description' => $this->faker->paragraph(), // text, nullable [cite: 22]
            'status' => $this->faker->randomElement(['todo', 'in_progress', 'done']), // enum [cite: 23]
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']), // enum [cite: 23]
            'due_date' => $this->faker->optional()->dateTimeBetween('now', '+1 month'), // date, nullable [cite: 24]
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
