<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address', 'phone', 'email', 'website'];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    // public function employees()
    // {
    //     return $this->hasManyThrough(Employee::class, Team::class);
    // }
}
