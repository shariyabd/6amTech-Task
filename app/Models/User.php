<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\ImportJob;
use App\Models\ImportStatistic;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function hasRole(string $roleSlug): bool
    {
        return $this->role->slug === $roleSlug;
    }

    public function import_job()
    {
        return $this->hasMany(ImportJob::class);
    }
    public function import_statistics()
    {
        return $this->hasManyThrough(
            ImportStatistic::class,
            ImportJob::class,
            'user_id',
            'import_id',
            'id',
            'id'
        );
    }
}
