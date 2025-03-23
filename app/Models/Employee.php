<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    protected $fillable = [
        'name',
        'email',
        'team_id',
        'salary',
        'start_date',
        'position',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'salary' => 'decimal:2',
    ];
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
