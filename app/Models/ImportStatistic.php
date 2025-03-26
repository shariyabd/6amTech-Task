<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportStatistic extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'import_id',
        'user_id',
        'total_records',
        'processed_records',
        'failed_records',
        'success_rate',
        'duration',
        'records_per_second',
        'completed_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function import_job()
    {
        return $this->belongsTo(ImportJob::class, 'import_id', 'id');
    }
}
