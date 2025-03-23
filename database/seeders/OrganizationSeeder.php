<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Organization::factory()->count(100000)->create();

        $organizations = [
            ['name' => 'TechCorp', 'industry' => 'Technology', 'location' => 'San Francisco', 'phone' => '123-456-7890', 'email' => 'info@techcorp.com', 'website' => 'www.techcorp.com', 'founded_year' => '2010'],
            ['name' => 'FinSolve', 'industry' => 'Finance', 'location' => 'New York', 'phone' => '987-654-3210', 'email' => 'contact@finsolve.com', 'website' => 'www.finsolve.com', 'founded_year' => '2015'],
            ['name' => 'HealthPlus', 'industry' => 'Healthcare', 'location' => 'Chicago', 'phone' => '555-111-2222', 'email' => 'support@healthplus.com', 'website' => 'www.healthplus.com', 'founded_year' => '2008'],
            ['name' => 'EduNation', 'industry' => 'Education', 'location' => 'Boston', 'phone' => '444-333-6666', 'email' => 'info@edunation.com', 'website' => 'www.edunation.com', 'founded_year' => '2000'],
            ['name' => 'GreenEnergy', 'industry' => 'Energy', 'location' => 'Houston', 'phone' => '777-888-9999', 'email' => 'contact@greenenergy.com', 'website' => 'www.greenenergy.com', 'founded_year' => '2012'],
            ['name' => 'Foodies', 'industry' => 'Food & Beverage', 'location' => 'Los Angeles', 'phone' => '666-777-8888', 'email' => 'info@foodies.com', 'website' => 'www.foodies.com', 'founded_year' => '2016'],
            ['name' => 'AutoMotiveX', 'industry' => 'Automobile', 'location' => 'Detroit', 'phone' => '999-000-1111', 'email' => 'support@automotivex.com', 'website' => 'www.automotivex.com', 'founded_year' => '1995'],
            ['name' => 'RetailGiant', 'industry' => 'Retail', 'location' => 'Seattle', 'phone' => '333-222-4444', 'email' => 'contact@retailgiant.com', 'website' => 'www.retailgiant.com', 'founded_year' => '1980'],
            ['name' => 'BuildTech', 'industry' => 'Construction', 'location' => 'Denver', 'phone' => '222-111-3333', 'email' => 'info@buildtech.com', 'website' => 'www.buildtech.com', 'founded_year' => '2005'],
            ['name' => 'CloudNet', 'industry' => 'Cloud Computing', 'location' => 'Austin', 'phone' => '555-999-6666', 'email' => 'contact@cloudnet.com', 'website' => 'www.cloudnet.com', 'founded_year' => '2018'],
        ];

        DB::table('organizations')->insert($organizations);
    }
}
