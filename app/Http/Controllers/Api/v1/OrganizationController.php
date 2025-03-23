<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\OrganizationUpdateRequest;
use Exception;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\OrganizationRequest;
use App\Http\Requests\OrganizationStoreRequest;

class OrganizationController extends BaseController
{
    public function index(Request $request)
    {
        try {
            $per_page      = $request->per_page ?? 10;
            $organizations = Organization::paginate($per_page);
            return $this->sendResponse($organizations->toArray(), 'All Organization Data');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(OrganizationStoreRequest $request)
    {
        try {
            $validated    = $request->validated();
            $organization = Organization::create($validated);
            return $this->sendResponse($organization->toArray(), "Organization Created Successfully");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $organization = Organization::find($id);
            if (!$organization) {
                $this->sendResponse([], "Organzation Not found");
            }
            return $this->sendResponse($organization->toArray(), "Single Organization Data");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function update(OrganizationUpdateRequest $request, $id)
    {
        $organization = Organization::find($id);

        if (!$organization) {
            return $this->sendResponse([], "Organization Not Found");
        }
        $validated = $request->validated();
        $organization->update($validated);
        return $this->sendResponse($organization->toArray(), "Organization Updated Successfully");
    }


    public function destroy($id)
    {
        try {
            $organization = Organization::find($id);
            if (!$organization) {
                return $this->sendResponse([], "Organization Not Found");
            }
            $organization->delete();
            return $this->sendResponse([], "Organization Deleted Successfully");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
