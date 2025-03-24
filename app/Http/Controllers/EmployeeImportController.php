<?php

namespace App\Http\Controllers;

use App\Models\ImportJob;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Events\EmployeeImportRequested;


class EmployeeImportController extends BaseController
{
    public function processImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'json_file' => 'required|file|mimes:json',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        }

        $file = $request->file('json_file');
        $path = $request->file('json_file')->store('imports');

        // import job record to track progress
        $import_job = ImportJob::create([
            'user_id' => Auth::id(),
            'file_path' => $path,
            'status' => 'pending',
            'job_id' => Str::uuid(),
            'total_records' => 0,
            'processed_records' => 0,
            'failed_records' => 0,
        ]);

        event(new EmployeeImportRequested($import_job));

        $result = [
            'progress_url' => url('api/v1/employees/import/status/' . $import_job->id)
        ];
        return $this->sendResponse($result, 'Your import has been queued and will be processed shortly');
    }

    public function showStatus($id)
    {
        $import_job = ImportJob::find($id);
        if (!$import_job) {
            return $this->sendResponse([], "Not Found");
        }
        return $this->sendResponse($import_job->toArray(), "Import Status");
    }
}
