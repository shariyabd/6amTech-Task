<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\OrganizationRequest;

class OrganizationController extends BaseController
{
    public function index()
    {
        $organizations = Organization::get();
        return $this->sendResponse($organizations->toArray(), 'All Organization Data');
    }

    public function store(OrganizationRequest $request)
    {
        $validated = $request->validated();
        $organization = Organization::create($validated);
        return $this->sendResponse($organization->toArray(), "Organization Created Successfully");
    }

    public function show($id)
    {
        $organization = Organization::find($id);
        if (empty($organization)) {
            $this->sendError("Organzation Not found");
        }
        return $this->sendResponse($organization->toArray(), "Single Organization Data");
    }

    public function update(Request $request, $id)
    {
        $organization = Organization::find($id);

        if (!$organization) {
            return $this->sendError("Organization Not Found");
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $organization->update($validator->validated());

        return $this->sendResponse($organization->toArray(), "Organization Updated Successfully");
    }


    public function destroy($id)
    {
        $organization = Organization::find($id);
        if (!$organization) {
            return $this->sendError("Organization Not Found");
        }
        $organization->delete();
        return response()->json(['message' => 'Organization deleted successfully'], 200);
    }
}
