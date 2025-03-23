<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\EmployeeUpdateRequest;
use Exception;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Http\Controllers\BaseController;
use App\Http\Requests\EmployeeStoreRequest;

class EmployeeController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $query = Employee::with(['team', 'organization']);

            //filter by start date
            if ($request->has('start_date')) {
                //scope query start date
                $query->startDate($request->start_date);
            }

            // filter by team
            if ($request->has('team_id')) {
                $query->where('team_id', $request->team_id);
            }

            //filter by organization
            if ($request->has('organization_id')) {
                $query->where('organization_id', $request->organization_id);
            }

            $per_page   = $request->per_page ?? 15;
            $result     =  $query->paginate($per_page);

            return $this->sendResponse($result->toArray(), "Employee List");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Store a newly created employee.
     *
     * @param Request $request
     */
    public function store(EmployeeStoreRequest $request)
    {
        try {
            $validated  = $request->validated();
            $employee   = Employee::create($validated);
            return $this->sendResponse($employee->toArray(), "Employee Created Successfully");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified employee.
     *
     * @param int $id
     */
    public function show($id)
    {
        try {
            $employee = Employee::with(['team', 'organization'])->find($id);
            if (!$employee) {
                return $this->sendResponse([], "Employee Not Found");
            }
            return $this->sendResponse($employee->toArray(), "Single Employee Data");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Update the specified employee.
     *
     * @param Request $request
     * @param int $id
     */
    public function update(EmployeeUpdateRequest $request, $id)
    {
        try {
            $employee = Employee::find($id);
            if (!$employee) {
                return $this->sendResponse([], "Employee Not Found");
            }
            $validated = $request->validated();
            $employee->update($validated);

            return $this->sendResponse($employee->fresh()->toArray(), "Employee Updated Successfully");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Remove the specified employee.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $employee = Employee::find($id);
            if (!$employee) {
                return $this->sendResponse([], "Employee Not Found");
            }
            $employee->delete();
            return $this->sendResponse([], "Employee has been deleted");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
