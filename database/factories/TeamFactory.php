<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'organization_id' => DB::table('organizations')->inRandomOrder()->value('id'),
            'department' => $this->faker->optional()->word,
        ];
    }
}
