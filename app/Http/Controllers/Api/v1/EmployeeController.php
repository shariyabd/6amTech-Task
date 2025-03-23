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
        $query = Employee::with(['team', 'organization']);

        //filter by start date
        if ($request->has('start_date')) {
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
    }

    /**
     * Store a newly created employee.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'team_id' => 'required|exists:teams,id',
            'organization_id' => 'required|exists:organizations,id',
            'salary' => 'required|numeric|min:0',
            'start_date' => 'required|date'
        ]);

        $employee = Employee::create($validated);

        return (new EmployeeResource($employee))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified employee.
     *
     * @param int $id
     */
    public function show($id)
    {
        $employee = Employee::with(['team', 'organization'])->findOrFail($id);
    }

    /**
     * Update the specified employee.
     *
     * @param Request $request
     * @param int $id
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:employees,email,' . $id,
            'team_id' => 'sometimes|exists:teams,id',
            'organization_id' => 'sometimes|exists:organizations,id',
            'salary' => 'sometimes|numeric|min:0',
            'start_date' => 'sometimes|date'
        ]);

        $employee->update($validated);
    }

    /**
     * Remove the specified employee.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return response()->json(null, 204);
    }
}
