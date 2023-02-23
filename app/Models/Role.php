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
    /**
     * role belongs to many user
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    /**
     * role belongs to many permission
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_roles');
    }
    /**
     * function for checking role has permissions is true or false
     */
    public function hasRole($modules, $permissions)
    {
        foreach ($this->permissions as $permission) {
            // dd($permission->hasPermission($module, $permission));
            return $permission->hasPermission($modules, $permissions);
        }
    }
}
