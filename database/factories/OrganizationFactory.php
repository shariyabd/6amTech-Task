<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrganizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Organization::class;

    public function definition(): array
    {
        return [
            'name'    => $this->faker->company,
            'address' => $this->faker->address,
            'phone'   => $this->faker->phoneNumber,
            'email'   => $this->faker->unique()->safeEmail,
            'website' => $this->faker->url,
        ];
    }
}
