<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_path',
        'timezone',
        'notifications',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'notifications' => 'array',
    ];

    // Optional helper:
    public function getAvatarUrlAttribute()
    {
        return $this->avatar_path ? \Storage::disk('public')->url($this->avatar_path) : null;
    }




    public function getDefaultGuardName(): string
    {
        return 'web';
    }
}
