<?php

declare(strict_types=1);

namespace App\Policies\Location;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Location\ShelfLevel;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShelfLevelPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ShelfLevel');
    }

    public function view(AuthUser $authUser, ShelfLevel $shelfLevel): bool
    {
        return $authUser->can('View:ShelfLevel');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ShelfLevel');
    }

    public function update(AuthUser $authUser, ShelfLevel $shelfLevel): bool
    {
        return $authUser->can('Update:ShelfLevel');
    }

    public function delete(AuthUser $authUser, ShelfLevel $shelfLevel): bool
    {
        return $authUser->can('Delete:ShelfLevel');
    }

    public function restore(AuthUser $authUser, ShelfLevel $shelfLevel): bool
    {
        return $authUser->can('Restore:ShelfLevel');
    }

    public function forceDelete(AuthUser $authUser, ShelfLevel $shelfLevel): bool
    {
        return $authUser->can('ForceDelete:ShelfLevel');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ShelfLevel');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ShelfLevel');
    }

    public function replicate(AuthUser $authUser, ShelfLevel $shelfLevel): bool
    {
        return $authUser->can('Replicate:ShelfLevel');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ShelfLevel');
    }

}