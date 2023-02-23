<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use App\Models\ModulePermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use Uuids, SoftDeletes;
    protected $dates    = ['deleted_at'];
    protected $fillable = ['name', 'description', 'created_by', 'updated_by', 'deleted_by', 'is_active', 'is_in_menu'];

    /**
     * permission belongs to many role
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    /**
     * permission has many module
     */
    public function modules()
    {
        return $this->hasMany(ModulePermission::class, 'permission_id');
    }
    /**
     * function for check permission have particuler access in module
     */
    public function hasPermission($modules, $permissions)
    {
        $module =  Module::where('module_code', $modules)->first();
        $data   = $this->modules()->where('module_id', $module->id)->where($permissions, true)->first();
        if ($data) {
            return true;
        } else {
            return false;
        }
    }
}
