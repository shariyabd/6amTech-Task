<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportJob extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'file_path',
        'status',
        'job_id',
        'total_records',
        'processed_records',
        'failed_records',
        'error_message'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
