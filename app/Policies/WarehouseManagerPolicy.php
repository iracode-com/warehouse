<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\WarehouseManager;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehouseManagerPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WarehouseManager');
    }

    public function view(AuthUser $authUser, WarehouseManager $warehouseManager): bool
    {
        return $authUser->can('View:WarehouseManager');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WarehouseManager');
    }

    public function update(AuthUser $authUser, WarehouseManager $warehouseManager): bool
    {
        return $authUser->can('Update:WarehouseManager');
    }

    public function delete(AuthUser $authUser, WarehouseManager $warehouseManager): bool
    {
        return $authUser->can('Delete:WarehouseManager');
    }

    public function restore(AuthUser $authUser, WarehouseManager $warehouseManager): bool
    {
        return $authUser->can('Restore:WarehouseManager');
    }

    public function forceDelete(AuthUser $authUser, WarehouseManager $warehouseManager): bool
    {
        return $authUser->can('ForceDelete:WarehouseManager');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WarehouseManager');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WarehouseManager');
    }

    public function replicate(AuthUser $authUser, WarehouseManager $warehouseManager): bool
    {
        return $authUser->can('Replicate:WarehouseManager');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WarehouseManager');
    }

}