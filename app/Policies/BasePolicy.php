<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Base;
use Illuminate\Auth\Access\HandlesAuthorization;

class BasePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Base');
    }

    public function view(AuthUser $authUser, Base $base): bool
    {
        return $authUser->can('View:Base');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Base');
    }

    public function update(AuthUser $authUser, Base $base): bool
    {
        return $authUser->can('Update:Base');
    }

    public function delete(AuthUser $authUser, Base $base): bool
    {
        return $authUser->can('Delete:Base');
    }

    public function restore(AuthUser $authUser, Base $base): bool
    {
        return $authUser->can('Restore:Base');
    }

    public function forceDelete(AuthUser $authUser, Base $base): bool
    {
        return $authUser->can('ForceDelete:Base');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Base');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Base');
    }

    public function replicate(AuthUser $authUser, Base $base): bool
    {
        return $authUser->can('Replicate:Base');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Base');
    }

}