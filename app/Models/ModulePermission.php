<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModulePermission extends Model
{
    use Uuids;
    use HasFactory;
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
