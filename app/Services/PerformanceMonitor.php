<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PerformanceMonitor
{
    protected $queryLog = [];
    protected $start_time;

    public function start_monitoring()
    {
        DB::enableQueryLog();
        $this->start_time = microtime(true);
        return $this;
    }

    public function end_monitoring($label = 'Query')
    {
        $execution_time  = microtime(true) - $this->start_time;
        $queries        = DB::getQueryLog();
        $totalQueries   = count($queries);

        $logData = [
            'label'             => $label,
            'execution_time'    => round($execution_time * 1000, 2) . 'ms',
            'total_queries'     => $totalQueries,
            'queries'           => $queries
        ];

        $this->queryLog[] = $logData;
        DB::flushQueryLog();
        return $logData;
    }

    public function getQueryLog()
    {
        return $this->queryLog;
    }
}
