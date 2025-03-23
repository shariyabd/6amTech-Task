<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{

    protected $fillable = [
        'name',
        'organization_id',
        'department',
    ];
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
