<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\WarehouseShed;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehouseShedPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WarehouseShed');
    }

    public function view(AuthUser $authUser, WarehouseShed $warehouseShed): bool
    {
        return $authUser->can('View:WarehouseShed');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WarehouseShed');
    }

    public function update(AuthUser $authUser, WarehouseShed $warehouseShed): bool
    {
        return $authUser->can('Update:WarehouseShed');
    }

    public function delete(AuthUser $authUser, WarehouseShed $warehouseShed): bool
    {
        return $authUser->can('Delete:WarehouseShed');
    }

    public function restore(AuthUser $authUser, WarehouseShed $warehouseShed): bool
    {
        return $authUser->can('Restore:WarehouseShed');
    }

    public function forceDelete(AuthUser $authUser, WarehouseShed $warehouseShed): bool
    {
        return $authUser->can('ForceDelete:WarehouseShed');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WarehouseShed');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WarehouseShed');
    }

    public function replicate(AuthUser $authUser, WarehouseShed $warehouseShed): bool
    {
        return $authUser->can('Replicate:WarehouseShed');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WarehouseShed');
    }

}