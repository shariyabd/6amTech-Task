<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\SalaryChangeLog;

class SaleryUpdateLogController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $salaryLog = SalaryChangeLog::query()
                ->selectRaw('id, employee_id, old_salary, new_salary')
                ->with(['employee', 'employee.organization', 'employee.team']);

            // Filter by employee name
            if ($request->has('employee_name')) {
                $salaryLog->whereHas('employee', function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->input('employee_name') . '%');
                });
            }

            // Filter by team
            if ($request->has('team_id')) {
                $salaryLog->whereHas('employee', function ($query) use ($request) {
                    $query->where('team_id', $request->input('team_id'));
                });
            }

            // Filter by organization
            if ($request->has('organization_id')) {
                $salaryLog->whereHas('employee', function ($query) use ($request) {
                    $query->where('organization_id', $request->input('organization_id'));
                });
            }
            $salaryLog =  $salaryLog->paginate($request->input('per_page', 15));

            return $this->sendResponse([
                'data' => $salaryLog->items(),
                'meta' => [
                    'current_page'  => $salaryLog->currentPage(),
                    'last_page'     => $salaryLog->lastPage(),
                    'total'         => $salaryLog->total(),
                    'per_page'      => $salaryLog->perPage(),
                ],
            ], " Salery Log List");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
