<?php

namespace Tests\Unit;

use App\Models\Team;
use App\Models\Employee;
use App\Models\Organization;
use App\Http\Controllers\Api\v1\ReportController;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_average_salary_per_team()
    {
        $organization = Organization::factory()->create();
        $team1 = Team::factory()->create([
            'name'  => 'Engineering',
            'organization_id' => $organization->id
        ]);
        $team2 = Team::factory()->create([
            'name' => 'Marketing',
            'organization_id' => $organization->id
        ]);

        Employee::factory()->count(3)->create([
            'team_id' => $team1->id,
            'salary'  => 50000,
        ]);
        Employee::factory()->count(2)->create([
            'team_id' => $team2->id,
            'salary'  => 60000,
        ]);

        $controller = new ReportController();
        $response   = $controller->avg_salery_per_team();

        // Decode response data
        $responseData = $response->getData(true)['data'];

        // Verify team count and calculated values
        $this->assertCount(2, $responseData['teams']);
        $this->assertEquals(2, $responseData['summary']['total_teams']);

        $team1Data = collect($responseData['teams'])->firstWhere('name', 'Engineering');
        $team2Data = collect($responseData['teams'])->firstWhere('name', 'Marketing');

        $this->assertEquals(50000, $team1Data['average_salary']);
        $this->assertEquals(60000, $team2Data['average_salary']);
    }
    public function it_handles_team_with_no_employees()
    {
        $organization = Organization::factory()->create();
        $team = Team::factory()->create(['name' => 'Empty Team', 'organization_id' => $organization->id]);

        $controller     = new ReportController();
        $response       = $controller->avg_salery_per_team();
        $responseData   = $response->getData(true)['data'];

        //Verify that the team with no employees has a null average_salary
        $emptyTeamData = collect($responseData['teams'])->firstWhere('name', 'Empty Team');
        $this->assertNull($emptyTeamData['average_salary']);
    }
    public function it_can_get_employees_per_organization()
    {

        $org1 = Organization::factory()->create(['name' => 'Tech Corp']);
        $org2 = Organization::factory()->create(['name' => 'Marketing Inc']);

        Employee::factory()->count(5)->create(['organization_id' => $org1->id]);
        Employee::factory()->count(3)->create(['organization_id' => $org2->id]);

        $controller     = new ReportController();
        $response       = $controller->employess_per_organization();
        $responseData   = $response->getData(true)['data'];

        // Verify structure and values
        $this->assertCount(2, $responseData['organization']);
        $this->assertEquals(2, $responseData['summary']['total_organizations']);
        $this->assertEquals(8, $responseData['summary']['total_employees']);

        $org1Data = collect($responseData['organization'])->firstWhere('name', 'Tech Corp');
        $org2Data = collect($responseData['organization'])->firstWhere('name', 'Marketing Inc');

        $this->assertEquals(5, $org1Data['employee_count']);
        $this->assertEquals(3, $org2Data['employee_count']);
    }

    public function it_handles_organization_with_no_employees()
    {

        $org            = Organization::factory()->create(['name' => 'Empty Org']);
        $controller     = new ReportController();
        $response       = $controller->employess_per_organization();
        $responseData   = $response->getData(true)['data'];

        // Verify that the organization has zero employees
        $emptyOrgData = collect($responseData['organization'])->firstWhere('name', 'Empty Org');
        $this->assertEquals(0, $emptyOrgData['employee_count']);
        $this->assertEquals(1, $responseData['summary']['total_organizations']);
        $this->assertEquals(0, $responseData['summary']['total_employees']);
    }
}
