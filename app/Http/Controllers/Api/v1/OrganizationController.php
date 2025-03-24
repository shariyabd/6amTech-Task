<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\OrganizationUpdateRequest;
use Exception;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use App\Http\Requests\OrganizationStoreRequest;
use Illuminate\Support\Facades\Cache;
use App\Services\PerformanceMonitor;

class OrganizationController extends BaseController
{
    protected $performance_monitor;

    public function __construct(PerformanceMonitor $performance_monitor)
    {
        $this->performance_monitor = $performance_monitor;
    }

    public function index(Request $request)
    {
        $this->performance_monitor->start_monitoring();

        try {
            $per_page   = $request->per_page ?? 10;
            $page       = $request->page ?? 1;
            $cacheKeys  = Cache::get('organization_cache_keys', []);
            $cacheKey   = "organizations_page_{$page}_per_page_{$per_page}";

            if (!in_array($cacheKey, $cacheKeys)) {
                $cacheKeys[] = $cacheKey;
                Cache::put('organization_cache_keys', $cacheKeys, now()->addMinutes(30));
            }

            $organizations = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($per_page) {
                return Organization::query()
                    ->select(['id', 'name'])
                    ->withCount(['teams', 'employees'])
                    ->paginate($per_page);
            });

            $performance_data = $this->performance_monitor->end_monitoring('Organization Index');

            return $this->sendResponse([
                'data' => $organizations->items(),
                'meta' => [
                    'current_page' => $organizations->currentPage(),
                    'last_page'    => $organizations->lastPage(),
                    'total'        => $organizations->total(),
                    'per_page'     => $organizations->perPage(),
                ],
                'performance' => $performance_data
            ], 'All Organization Data');
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Organization Index Error');
            return $this->sendError($e->getMessage());
        }
    }



    public function store(OrganizationStoreRequest $request)
    {
        $this->performance_monitor->start_monitoring();

        try {
            $validated      = $request->validated();
            $organization   = Organization::create($validated);

            $this->clearOrganizationCaches();

            $performanceData = $this->performance_monitor->end_monitoring('Organization Store');

            return $this->sendResponse([
                'organization' => $organization->toArray(),
                'performance' => $performanceData
            ], "Organization Created Successfully");
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Organization Store Error');
            return $this->sendError($e->getMessage());
        }
    }

    public function show($id)
    {
        $this->performance_monitor->start_monitoring();

        try {
            $cacheKey     = "organization_{$id}";
            $organization = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($id) {
                return Organization::withCount(['teams', 'employees'])->find($id);
            });
            if (!$organization) {
                return $this->sendResponse([], "Organization Not found");
            }
            $performanceData = $this->performance_monitor->end_monitoring('Organization Show');

            return $this->sendResponse([
                'organization' => $organization->toArray(),
                'performance' => $performanceData
            ], "Single Organization Data");
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Organization Show Error');
            return $this->sendError($e->getMessage());
        }
    }

    public function update(OrganizationUpdateRequest $request, $id)
    {
        $this->performance_monitor->start_monitoring();

        try {
            $organization = Organization::find($id);
            if (!$organization) {
                return $this->sendResponse([], "Not Found");
            }
            $validated = $request->validated();
            $organization->update($validated);

            $this->clearOrganizationCaches($id);
            $performanceData = $this->performance_monitor->end_monitoring('Organization Update');

            return $this->sendResponse([
                'organization' => $organization->fresh()->toArray(),
                'performance' => $performanceData
            ], "Organization Updated Successfully");
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Organization Update Error');
            return $this->sendError($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $this->performance_monitor->start_monitoring();

        try {
            $organization = Organization::find($id);
            if (!$organization) {
                return $this->sendResponse([], "Not Found");
            }
            $organization->delete();

            $this->clearOrganizationCaches($id);
            $performanceData = $this->performance_monitor->end_monitoring('Organization Delete');

            return $this->sendResponse([
                'performance' => $performanceData
            ], "Organization Deleted Successfully");
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Organization Delete Error');
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Clear organization-related caches
     */
    protected function clearOrganizationCaches($id = null)
    {
        if ($id) {
            Cache::forget("organization_{$id}");
        }
        $cacheKeys = Cache::get('organization_cache_keys', []);
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        Cache::forget('organization_cache_keys');
    }
}
