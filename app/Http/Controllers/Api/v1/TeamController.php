<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Requests\TeamRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;

class TeamController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::with('organization')->paginate(10);
        if ($teams->isEmpty()) {
            return $this->sendError("Data Not Found");
        }

        return $this->sendResponse($teams->toArray(), 'Teams retrieved successfully');
    }

    /**
     * Store or update the resource in storage.
     */
    public function store(TeamRequest $request, $id = null)
    {
        if ($id) {
            $team = Team::find($id);
            if (!$team) {
                return $this->sendError('Team not found');
            }
            $team->update($request->validated());
            $message = 'Team updated successfully';
        } else {
            $team = Team::create($request->validated());
            $message = 'Team created successfully';
        }

        return $this->sendResponse($team->toArray(), $message);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $team = Team::with('organization')->find($id);

        if (!$team) {
            return $this->sendError('Team not found');
        }

        return $this->sendResponse($team->toArray(), 'Team retrieved successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $team = Team::find($id);

        if (!$team) {
            return $this->sendError('Team not found');
        }
        $team->delete();

        return $this->sendResponse(null, 'Team deleted successfully');
    }
}
