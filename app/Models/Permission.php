<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use App\Models\ModulePermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use Uuids;
    use HasFactory;
    protected $fillable = ['name', 'description', 'created_by', 'updated_by', 'deleted_by', 'is_active', 'is_in_menu'];
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function modulepermission()
    {
        return $this->hasMany(ModulePermission::class, 'permission_id');
    }
}
