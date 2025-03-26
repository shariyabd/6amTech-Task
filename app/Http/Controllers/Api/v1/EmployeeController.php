<?php

namespace App\Http\Controllers\Api\v1;

use Exception;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Services\PerformanceMonitor;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\BaseController;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;


class EmployeeController extends BaseController
{
    protected $performance_monitor;

    public function __construct(PerformanceMonitor $performance_monitor)
    {
        $this->performance_monitor = $performance_monitor;
    }

    public function index(Request $request)
    {
        try {
            $this->performance_monitor->start_monitoring();
            $per_page   = $request->per_page ?? 15;
            $page       = $request->page ?? 1;
            $query      = Employee::with(['team', 'organization']);

            //  filters
            if ($request->has('start_date')) {
                $query->startDate($request->start_date);
            }
            if ($request->has('team_id')) {
                $query->where('team_id', $request->team_id);
            }
            if ($request->has('organization_id')) {
                $query->where('organization_id', $request->organization_id);
            }

            $employees = $query->paginate($per_page);

            $performanceData = $this->performance_monitor->end_monitoring('Employee Index');

            return $this->sendResponse([
                'data' => $employees->items(),
                'meta' => [
                    'current_page' => $employees->currentPage(),
                    'last_page' => $employees->lastPage(),
                    'total' => $employees->total(),
                    'per_page' => $employees->perPage(),
                ],
                'performance' => $performanceData
            ], "Filtered Employee List");
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Employee Index Error');
            return $this->sendError($e->getMessage());
        }
    }


    public function store(EmployeeStoreRequest $request)
    {
        $this->performance_monitor->start_monitoring();

        try {
            $validated  = $request->validated();
            $employee   = Employee::create($validated);

            $this->clearEmployeeCaches();

            $performance_data = $this->performance_monitor->end_monitoring('Employee Store');

            return $this->sendResponse([
                'employee' => $employee->toArray(),
                'performance' => $performance_data
            ], "Employee Created Successfully");
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Employee Store Error');
            return $this->sendError($e->getMessage());
        }
    }

    public function show($id)
    {
        $this->performance_monitor->start_monitoring();

        try {
            $cacheKey = "employee_{$id}";

            $employee = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($id) {
                return Employee::with(['team', 'organization'])->find($id);
            });

            if (!$employee) {
                return $this->sendResponse([], "Employee Not Found");
            }

            $performance_data = $this->performance_monitor->end_monitoring('Employee Show');

            return $this->sendResponse([
                'employee' => $employee->toArray(),
                'performance' => $performance_data
            ], "Single Employee Data");
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Employee Show Error');
            return $this->sendError($e->getMessage());
        }
    }

    public function update(EmployeeUpdateRequest $request, $id)
    {
        $this->performance_monitor->start_monitoring();

        try {
            $employee = Employee::find($id);
            if (!$employee) {
                return $this->sendResponse([], "Employee Not Found");
            }
            $validated = $request->validated();
            $employee->update($validated);

            $this->clearEmployeeCaches($id);
            $performance_data = $this->performance_monitor->end_monitoring('Employee Update');

            return $this->sendResponse([
                'employee' => $employee->fresh()->toArray(),
                'performance' => $performance_data
            ], "Employee Updated Successfully");
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Employee Update Error');
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $this->performance_monitor->start_monitoring();

        try {
            $employee = Employee::find($id);
            if (!$employee) {
                return $this->sendResponse([], "Employee Not Found");
            }
            $employee->delete();

            $this->clearEmployeeCaches($id);
            $performance_data = $this->performance_monitor->end_monitoring('Employee Delete');

            return $this->sendResponse([
                'performance' => $performance_data
            ], "Employee Deleted Successfully");
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Employee Delete Error');
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Clear employee-related caches
     */
    protected function clearEmployeeCaches($id = null)
    {

       
    }
}
