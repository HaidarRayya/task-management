<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'user_id';
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
    ];

    protected $guarded = [
        'password',
        'role',
        'created_date',
        'updated_date'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_date' => "datetime:Y-m-d H:i",
        'updated_date' => "datetime:Y-m-d H:i"
    ];
    protected $perPage = 6;

    public function employee_tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class, 'manager_id');
    }
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }


    public function getRoleAttribute($value)
    {
        $role = '';
        switch ($value) {
            case 1:
                $role = 'admin';
                break;
            case 2:
                $role = 'manager';
                break;
            case 3:
                $role = 'employee';
                break;
        }
        return $role;
    }
    public function setRoleAttribute($value)
    {
        $role = '';
        switch ($value) {
            case 'admin':
                $role = 1;
                break;
            case 'manager':
                $role = 2;
                break;
            case 'employee':
                $role = 3;
                break;
        }
        $this->attributes['role'] = $role;
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function scopeEmployees(Builder $query)
    {
        return $query->where('role', '=', 3);
    }

    public function scopeManagers(Builder $query)
    {
        return $query->where('role', '=', 2);
    }
    public function scopeNotAdmin(Builder $query)
    {
        return $query->where('role', '!=', 1);
    }
    public function scopeByRole(Builder $query, $role)
    {
        if ($role) {
            $r = '';
            switch ($role) {
                case 'manager':
                    $r = 2;
                    break;
                case 'employee':
                    $r = 3;
                    break;
            }
            return $query->where('role', '=', $r);
        } else {
            return $query;
        }
    }
    public function scopeByUserName(Builder $query, $user_name)
    {
        if ($user_name)
            return $query->where('name', 'LIKE', "%$user_name%");
        else
            return $query;
    }
}
