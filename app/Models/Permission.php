<?php

namespace App\Models;

use App\models\RoleHasPermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'guard_name', 'title'
    ];

    public function rolepermission()
    {
        return $this->hasMany('App\Models\RoleHasPermission', 'permission_id', 'id');
    }
}
