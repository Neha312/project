<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    use HasFactory;
    public $table = "role_users";
    protected $fillable = ['users_id', 'role_id', 'is_active'];
}
