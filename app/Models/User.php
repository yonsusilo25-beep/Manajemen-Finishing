<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'role',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isWarehouse()
    {
        return in_array($this->role, ['warehouse', 'manager'], true);
    }

    public function isProduction()
    {
        return in_array($this->role, ['production', 'manager'], true);
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isSupervisor()
    {
        return $this->role === 'supervisor';
    }

    public function isForeman()
    {
        return $this->role === 'foreman';
    }
}
