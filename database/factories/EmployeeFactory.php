<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Employee::class;

    public function definition()
    {
        // random team and its organization
        $team = Team::inRandomOrder()->first();

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'team_id' => $team->id,
            'organization_id' => $team->organization_id,
            'salary' => $this->faker->randomFloat(2, 30000, 150000),
            'start_date' => $this->faker->date(),
            'position' => $this->faker->jobTitle,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
