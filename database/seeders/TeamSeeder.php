<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Team::factory()->count(100000)->create();

        $organizationIds = DB::table('organizations')->pluck('id')->toArray();

        $teams = [
            ['name' => 'Software Development', 'organization_id' => $organizationIds[0], 'department' => 'Engineering'],
            ['name' => 'Marketing', 'organization_id' => $organizationIds[0], 'department' => 'Sales'],
            ['name' => 'Risk Management', 'organization_id' => $organizationIds[1], 'department' => 'Finance'],
            ['name' => 'Customer Support', 'organization_id' => $organizationIds[2], 'department' => 'Support'],
            ['name' => 'Research & Development', 'organization_id' => $organizationIds[2], 'department' => 'Innovation'],
            ['name' => 'Sales', 'organization_id' => $organizationIds[3], 'department' => 'Business'],
            ['name' => 'Human Resources', 'organization_id' => $organizationIds[3], 'department' => 'HR'],
            ['name' => 'Data Analytics', 'organization_id' => $organizationIds[4], 'department' => 'IT'],
            ['name' => 'Public Relations', 'organization_id' => $organizationIds[4], 'department' => 'Communications'],
            ['name' => 'Cybersecurity', 'organization_id' => $organizationIds[5], 'department' => 'IT Security'],
            ['name' => 'Cloud Infrastructure', 'organization_id' => $organizationIds[6], 'department' => 'IT'],
            ['name' => 'Product Management', 'organization_id' => $organizationIds[6], 'department' => 'Product'],
            ['name' => 'Operations', 'organization_id' => $organizationIds[7], 'department' => 'Business'],
            ['name' => 'IT Support', 'organization_id' => $organizationIds[7], 'department' => 'IT'],
            ['name' => 'Logistics', 'organization_id' => $organizationIds[8], 'department' => 'Supply Chain'],
            ['name' => 'Legal Compliance', 'organization_id' => $organizationIds[8], 'department' => 'Legal'],
            ['name' => 'Business Intelligence', 'organization_id' => $organizationIds[9], 'department' => 'Data Science'],
            ['name' => 'Financial Planning', 'organization_id' => $organizationIds[9], 'department' => 'Finance'],
            ['name' => 'Customer Relations', 'organization_id' => $organizationIds[0], 'department' => 'Support'],
            ['name' => 'UX/UI Design', 'organization_id' => $organizationIds[1], 'department' => 'Design'],
        ];

        DB::table('teams')->insert($teams);
    }
}
