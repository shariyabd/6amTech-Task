<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeRequest;
use App\Http\Controllers\BaseController;

class EmployeeController extends BaseController
{
    public function index(Request $request)
    {
        $query = Employee::with('team');

        // Optional filtering by team
        if ($request->has('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        // Optional date range filtering
        if ($request->has('start_date_from')) {
            $query->where('start_date', '>=', $request->start_date_from);
        }

        if ($request->has('start_date_to')) {
            $query->where('start_date', '<=', $request->start_date_to);
        }

        // Optional position filtering
        if ($request->has('position')) {
            $query->where('position', $request->position);
        }

        // Sorting
        $sortField = $request->input('sort_by', 'id');
        $sortDirection = $request->input('sort_direction', 'asc');
        $allowedSortFields = ['id', 'name', 'salary', 'start_date', 'position'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $employees = $query->paginate($request->input('per_page', 10));

        return $this->sendResponse($employees->toArray(), 'Employees retrieved successfully');
    }

    /**
     * Store or update the resource in storage.
     */
    public function save(EmployeeRequest $request,  $id = null)
    {
        if ($id) {
            $employee = Employee::find($id);

            if (!$employee) {
                return $this->sendError('Employee not found');
            }
            $employee->update($request->validated());
            $message = 'Employee updated successfully';
        } else {
            $employee = Employee::create($request->validated());
            $message = 'Employee created successfully';
        }

        return $this->sendResponse($employee->toArray(), $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Employee::with('team')->find($id);

        if (!$employee) {
            return $this->sendError('Employee not found');
        }

        return $this->sendResponse($employee->toArray(), 'Employee retrieved successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return $this->sendError('Employee not found');
        }

        $employee->delete();

        return $this->sendResponse(null, 'Employee deleted successfully');
    }
}
