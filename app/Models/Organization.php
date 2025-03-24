<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'industry',
        'location',
        'phone',
        'email',
        'website',
        'founded_year',

    ];


    public function teams()
    {
        return $this->hasMany(Team::class)->select(['id', 'organization_id', 'name']);
    }

    public function employees()
    {
        return $this->hasManyThrough(Employee::class, Team::class)->select(['employees.id', 'employees.team_id', 'employees.first_name', 'employees.last_name', 'employees.email']);
    }
}
