<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => null,
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(Task::STATUSES),
            'priority' => fake()->randomElement(Task::PRIORITIES),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+3 months'),
        ];
    }

    public function todo(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Task::STATUS_TODO,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Task::STATUS_IN_PROGRESS,
        ]);
    }

    public function done(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Task::STATUS_DONE,
        ]);
    }

    public function blocked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Task::STATUS_BLOCKED,
        ]);
    }

    public function low(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => Task::PRIORITY_LOW,
        ]);
    }

    public function medium(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => Task::PRIORITY_MEDIUM,
        ]);
    }

    public function high(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => Task::PRIORITY_HIGH,
        ]);
    }
}
