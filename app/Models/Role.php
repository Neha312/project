<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use Uuids, SoftDeletes;
    protected $dates    = ['deleted_at'];
    protected $fillable = ['name', 'description', 'is_active', 'deleted_at', 'is_delete'];

    //function for role belongs to many users
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    //function for role belongs to many permissions
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_roles');
    }
    //function for checking role has permissions is true or false
    public function hasRole($module, $permission)
    {
        return $this->permissions()->first()->hasPermission($module, $permission);
    }
}
