<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Team;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;

class ReportController extends BaseController
{
    public function avg_salery_per_team()
    {
        $teams = Team::withAvg('employees', 'salary')
            ->get()
            ->map(function ($team) {
                return [
                    'id' => $team->id,
                    'name' => $team->name,
                    'average_salary' => round($team->employees_avg_salary, 2)
                ];
            });

        $result = [
            'teams' => $teams,
            'summary' => [
                'total_teams' => $teams->count(),
                'overall_average' => round($teams->avg('average_salary'), 2)
            ]
        ];

        return $this->sendResponse($result, "Avarage Salery Per Employee");
    }


    public function employess_per_organization()
    {
        $organizations = Organization::withCount('employees')
            ->get()
            ->map(function ($organization) {
                return [
                    'id' => $organization->id,
                    'name' => $organization->name,
                    'employee_count' => $organization->employees_count
                ];
            });

        $result = [
            'organization' => $organizations,
            'summary' => [
                'total_organizations' => $organizations->count(),
                'total_employees' => $organizations->sum('employee_count')
            ]
        ];

        return $this->sendResponse($result, "Organization Wise Employess");
    }
}
