<?php

namespace App\Models;

use App\Http\Traits\Uuids;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Uuids, SoftDeletes;

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'is_active',
        'is_first_login',
        'code',
        'type',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_deleted'
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
    ];
    /**
     * user belong to many role
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }
    /**
     * function for checking user has roles is true or false
     */
    public function hasAccess($modules, $permissions)
    {
        foreach ($this->roles as $role) {
            // dd($role);
            return $role->hasRole($modules, $permissions);
        }
    }
}
