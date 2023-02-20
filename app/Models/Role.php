<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use Uuids;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'description', 'is_active', 'deleted_at', 'is_delete'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_roles');
    }
}
