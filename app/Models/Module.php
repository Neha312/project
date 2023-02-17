<?php

namespace App\Models;

use App\Http\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use Uuids;
    use HasFactory;
    protected $fillable = ['module_code', 'name', 'created_by', 'updated_by', 'deleted_by', 'is_active', 'is_in_menu', 'display_order'];
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
