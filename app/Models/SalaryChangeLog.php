<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryChangeLog extends Model
{
    protected $fillable = [
        'employee_id',
        'old_salary',
        'new_salary',
        'changed_by',
        'change_reason'
    ];

    protected $casts = [
        'old_salary' => 'decimal:2',
        'new_salary' => 'decimal:2'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class)->selectRaw('id, name, salary, team_id,organization_id');
    }
}
