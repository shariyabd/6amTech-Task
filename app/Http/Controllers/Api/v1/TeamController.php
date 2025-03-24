<?php

namespace App\Http\Controllers\Api\v1;

use Exception;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Requests\TeamRequest;
use App\Http\Controllers\Controller;
use App\Services\PerformanceMonitor;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\TeamStoreRequest;
use App\Http\Controllers\BaseController;
use App\Http\Requests\TeamUpdateRequest;

class TeamController extends BaseController
{
    /**
     * Display a listing of the resource.
     */

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
            $cacheKeys  = Cache::get('team_cache_keys', []);
            $cacheKey   = "teams_page_{$page}_per_page_{$per_page}";

            if (!in_array($cacheKey, $cacheKeys)) {
                $cacheKeys[] = $cacheKey;
                Cache::put('team_cache_keys', $cacheKeys, now()->addMinutes(30));
            }

            $teams = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($per_page) {
                return Team::query()->selectRaw('id,name,organization_id, department')->with('organization')->paginate($per_page);
            });

            $performance_data = $this->performance_monitor->end_monitoring('Team Index');

            return $this->sendResponse([
                'data' => $teams->items(),
                'meta' => [
                    'current_page' => $teams->currentPage(),
                    'last_page'    => $teams->lastPage(),
                    'total'        => $teams->total(),
                    'per_page'     => $teams->perPage(),
                ],
                'performance' => $performance_data
            ], 'All Organization Data');
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Team Index Error');
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Store or update the resource in storage.
     */
    public function store(TeamStoreRequest $request)
    {
        $this->performance_monitor->start_monitoring();
        try {
            $validated = $request->validated();
            $team = Team::create($validated);
            $this->clearOrganizationCaches();
            $performanceData = $this->performance_monitor->end_monitoring('Organization Store');

            return $this->sendResponse([
                'team' => $team->toArray(),
                'performance' => $performanceData
            ], "Team Created Successfully");
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Team Store Error');
            return $this->sendError($e->getMessage());
        }
    }


    public function update(TeamUpdateRequest $request, $id)
    {
        $this->performance_monitor->start_monitoring();
        try {
            $team = Team::find($id);
            if (!$team) {
                return $this->sendResponse([], "Team Not Found");
            }
            $validated = $request->validated();
            $team->update($validated);
            $this->clearOrganizationCaches($id);

            $performanceData = $this->performance_monitor->end_monitoring('Organization Update');

            return $this->sendResponse([
                'organization' => $team->fresh()->toArray(),
                'performance' => $performanceData
            ], "Team Updated Successfully");
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Team Update Error');
            return $this->sendError($e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->performance_monitor->start_monitoring();
        try {
            $cacheKey     = "team_{$id}";
            $team = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($id) {
                return Team::with('organization')->find($id);
            });
            if (!$team) {
                return $this->sendResponse([], 'Team not found');
            }
            $performanceData = $this->performance_monitor->end_monitoring('Team Show');

            return $this->sendResponse([
                'organization' => $team->toArray(),
                'performance' => $performanceData
            ], "Single Team Data");
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Team Show Error');
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->performance_monitor->start_monitoring();
        try {
            $team = Team::find($id);
            if (!$team) {
                return $this->sendResponse([], 'Team not found');
            }
            $team->delete();
            $this->clearOrganizationCaches($id);
            $performanceData = $this->performance_monitor->end_monitoring('Team Delete');

            return $this->sendResponse([
                'performance' => $performanceData
            ], "Team Deleted Successfully");
        } catch (Exception $e) {
            $this->performance_monitor->end_monitoring('Team Delete Error');
            return $this->sendError($e->getMessage());
        }
    }

    protected function clearOrganizationCaches($id = null)
    {
        if ($id) {
            Cache::forget("oteam_{$id}");
        }
        $cacheKeys = Cache::get('team_cache_keys', []);
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        Cache::forget('team_cache_keys');
    }
}
