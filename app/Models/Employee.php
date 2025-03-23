<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'team_id',
        'organization_id',
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
        'salary' => 'float',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class)->selectRaw('id,name,department');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class)->selectRaw('id, name, industry,location');
    }
    // public function organization()
    // {
    //     return $this->team->organization();
    // }


    public function scopeStartDate($query, $start_date)
    {
        return $query->where('start_date', $start_date);
    }
}
