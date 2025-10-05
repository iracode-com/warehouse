<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

Route::get('/clear_cache', function () {
    Artisan::call('optimize');
    return ['success' => true];
});


Route::get('sync-permissions', function () {
    $super_admin_role = \App\Models\Role::where('name', 'like', '%super_admin%')->first();
    foreach (\App\Models\Permission::all()->toArray() as $permission) {
        $p_id = $permission['id'];
        $role_id = $super_admin_role['id'];
        \App\Models\RoleHasPermission::firstOrCreate([
            'permission_id' => $p_id,
            'role_id' => $role_id
        ], [
            'permission_id' => $p_id,
            'role_id' => $role_id
        ]);
    }
    return ['message' => 'done'];
});
