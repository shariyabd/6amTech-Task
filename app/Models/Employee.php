<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function organization()
    {
        return $this->team->organization();
    }


    public function scopeStartedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate]);
    }
}
