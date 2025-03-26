<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;

class ImportStatisticController extends BaseController
{
    public function index()
    {
        $users = User::with('import_statistics')->get();

        $statistics = $users->pluck('import_statistics')->flatten()->map(function ($statistic) {
            return [
                'import_job_id'        => $statistic->import_job_id,
                'user_id'              => $statistic->user_id,
                'total_records'        => $statistic->total_records,
                'processed_records'    => $statistic->processed_records,
                'failed_records'       => $statistic->failed_records,
                'success_rate'         => $statistic->success_rate,
                'duration'             => $statistic->duration,
                'records_per_second'   => $statistic->records_per_second,
                'completed_at'         => $statistic->completed_at,
            ];
        });

        $result =  [
            'total_statistics' => $statistics->count(),
            'statistics'       => $statistics,
        ];

        return $this->sendResponse($result, "Employee Import Statistics");
    }
}
