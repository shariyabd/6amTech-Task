<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\ImportJob;
use Illuminate\Support\Facades\DB;
use App\Notifications\ImportFailed;
use Illuminate\Support\Facades\Log;
use App\Events\EmployeeSalaryUpdatedEvent;
use App\Notifications\ImportProgress;
use App\Notifications\ImportCompleted;
use App\Events\EmployeeImportCompletedEvent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessEmployeeImport implements ShouldQueue
{
    use Queueable;
    public $import_job;

    public $tries = 3;
    public $timeout = 3600;

    public function __construct(ImportJob $import_job)
    {
        $this->import_job = $import_job;
    }

    public function handle()
    {
        // updated job status to processing
        $this->import_job->update(['status' => 'processing']);

        try {
            $jsonContent = Storage::get($this->import_job->file_path);
            $employees = json_decode($jsonContent, true);

            if (!is_array($employees)) {
                return response()->json(['status' => false, 'error' => "Invalid Json File"]);
            }

            $this->import_job->update([
                'total_records' => count($employees)
            ]);

            foreach ($employees as $index => $employeeData) {
                $this->processEmployeeRecord($employeeData);

                // Update progress
                $this->import_job->increment('processed_records');

                // Send progress notification after every 10% of records
                if ($index % max(1, intval(count($employees) / 10)) === 0) {
                    $this->sendProgressNotification();
                }
            }

            // Mark job as completed
            $this->import_job->update(['status' => 'completed']);

            // Notify user of completion
            $this->import_job->user->notify(new ImportCompleted($this->import_job));

            // Dispatch completion event
            event(new EmployeeImportCompletedEvent($this->import_job));
        } catch (\Exception $e) {
            // Log the error
            Log::error("Import failed: " . $e->getMessage(), [
                'import_job_id' => $this->import_job->id,
                'exception' => $e
            ]);

            // Update job status
            $this->import_job->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);

            // Notify user of failure
            $this->import_job->user->notify(new ImportFailed($this->import_job));

            throw $e;
        }
    }

    protected function processEmployeeRecord($data)
    {
        try {
            DB::beginTransaction();

            if (empty($data['email']) || empty($data['name'])) {
                return response()->json(['status' => false, 'error' => "Employee record is missing required fields"]);
            }

            // Find or create
            $employee = Employee::firstOrNew(['email' => $data['email']]);

            // Check if this is an update that changes the salary
            $oldSalary = $employee->exists ? $employee->salary : null;

            // Update employee data
            $employee->fill([
                'name'              => $data['name'],
                'email'             => $data['email'] ?? null,
                'team_id'           => $data['team_id'] ?? null,
                'organization_id'   => $data['organization_id'] ?? null,
                'salary'            => $data['salary'] ?? null,
                'start_date'        => $data['start_date'] ?? null,
                'position'          => $data['position'] ?? null,
            ]);

            $employee->save();

            // If salary  updated, dispatch  event
            if ($oldSalary !== null && $employee->salary !== $oldSalary) {
                event(new EmployeeSalaryUpdatedEvent($employee, $oldSalary, $employee->salary));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            // Increment failed records counter
            $this->import_job->increment('failed_records');

            Log::error("Failed to process employee record: " . $e->getMessage(), [
                'data' => $data,
                'exception' => $e
            ]);
        }
    }

    protected function sendProgressNotification()
    {
        try {
            $progress = ($this->import_job->processed_records / $this->import_job->total_records) * 100;
            $this->import_job->user->notify(new ImportProgress($this->import_job, $progress));
        } catch (\Exception $e) {
            Log::error("Failed to send progress notification: " . $e->getMessage());
        }
    }
}
