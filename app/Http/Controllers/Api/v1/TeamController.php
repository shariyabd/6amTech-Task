<?php

namespace App\Http\Controllers\Api\v1;

use Exception;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Requests\TeamRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeamStoreRequest;
use App\Http\Controllers\BaseController;
use App\Http\Requests\TeamUpdateRequest;

class TeamController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $per_page = $request->per_page ?? 10;
            $teams = Team::with('organization')->paginate($per_page);
            if ($teams->isEmpty()) {
                return $this->sendResponse([], "Data Not Found");
            }
            return $this->sendResponse($teams->toArray(), 'Teams retrieved successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Store or update the resource in storage.
     */
    public function store(TeamStoreRequest $request)
    {
        try {
            $validated = $request->validated();
            $team = Team::create($validated);
            return $this->sendResponse($team->toArray(), "Team Created Successfully");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }


    public function update(TeamUpdateRequest $request, $id)
    {
        try {
            $team = Team::find($id);
            if (!$team) {
                return $this->sendResponse([], "Team Not Found");
            }
            $validated = $request->validated();
            $team->update($validated);
            return $this->sendResponse($team->fresh()->toArray(), "Team Updated Successfully");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $team = Team::with('organization')->find($id);
            if (!$team) {
                return $this->sendResponse([], 'Team not found');
            }
            return $this->sendResponse($team->toArray(), 'Team retrieved successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $team = Team::find($id);
            if (!$team) {
                return $this->sendResponse([], 'Team not found');
            }
            $team->delete();
            return $this->sendResponse([], 'Team deleted successfully');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
