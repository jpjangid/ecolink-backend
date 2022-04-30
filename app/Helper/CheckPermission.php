<?php

use Illuminate\Support\Facades\DB;

function checkpermission($permissionName)
{
    $permission = DB::table('permissions')->where('name', $permissionName)->first();

    $user_id = auth()->user()->id;

    $userpermission = DB::table('role_has_permissions')->where(['user_id' => $user_id, 'permission_id' => $permission->id])->first();

    if(!empty($userpermission)){
        return true;
    }else{
        return false;
    }
}