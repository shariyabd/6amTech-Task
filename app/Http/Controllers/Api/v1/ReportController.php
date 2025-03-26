<?php

namespace App\Http\Controllers\Api\v1;

use Exception;
use App\Models\Team;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;


class ReportController extends BaseController
{
    public function avg_salery_per_team()
    {
        try {
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
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function employess_per_organization()
    {
        try {
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
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    public function employee_report()
    {
        try {

            $organizations = Organization::with(['teams.employees'])->get();

            $report = $organizations->map(function ($org) {
                //  organization-level aggregates
                $org_employees       = $org->employees;
                $total_org_employees = $org_employees->count();
                $average_org_salary  = $org_employees->avg('salary');

                //  teams data
                $teams_data = $org->teams->map(function ($team) {
                    // Get employees
                    $employees              = $team->employees;
                    $total_team_employees   = $employees->count();
                    $average_team_salary    = $employees->avg('salary');

                    //  employee tenure
                    $employees_data     = $employees->map(function ($employee) {
                        $tenure_years   = $employee->start_date ? $employee->start_date->diffInYears(now()) : null;
                        return [
                            'name'             => $employee->name,
                            'email'            => $employee->email,
                            'position'         => $employee->position,
                            'salary'           => $employee->salary,
                            'start_date'       => $employee->start_date ? $employee->start_date->format('Y-m-d') : null,
                            'tenure_years'     => $tenure_years,
                        ];
                    });

                    return [
                        'team_id'           => $team->id,
                        'team_name'         => $team->name,
                        'department'        => $team->department,
                        'total_employees'   => $total_team_employees,
                        'average_salary'    => $average_team_salary,
                        'employees'         => $employees_data,
                    ];
                });

                return [
                    'organization_id'     => $org->id,
                    'organization_name'   => $org->name,
                    'industry'            => $org->industry,
                    'location'            => $org->location,
                    'total_employees'     => $total_org_employees,
                    'average_salary'      => $average_org_salary,
                    'teams'               => $teams_data,
                ];
            });

            $result = [
                'total_organizations' => $organizations->count(),
                'report'              => $report,
            ];

            return $this->sendResponse($result, 'Employee Report');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
