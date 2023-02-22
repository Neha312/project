<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ModulePermission extends Model
{
    use Uuids, SoftDeletes;

    protected $dates    = ['deleted_at'];
    protected $fillable = [
        'peermission_id',
        'module_id',
        'add_access',
        'edit_access',
        'delete_access',
        'view_access',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at',
        'is_deleted'
    ];
}
