<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    use HasFactory;
    public $table = 'permission_roles';
    protected $fillable = ['role_id', 'permission_id', 'is_active'];
}
