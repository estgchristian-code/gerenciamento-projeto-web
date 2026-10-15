<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $startDate = fake()->optional(0.8)->dateTimeBetween('-6 months', 'now');

        return [
            'user_id' => null,
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['planejamento', 'em_andamento', 'concluido', 'cancelado']),
            'start_date' => $startDate,
            'due_date' => fake()->optional(0.8)->dateTimeBetween($startDate ?? '-6 months', '+1 year'),
        ];
    }
}
