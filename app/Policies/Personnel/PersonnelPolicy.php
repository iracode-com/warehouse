<?php

declare(strict_types=1);

namespace App\Policies\Personnel;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Personnel\Personnel;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonnelPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Personnel');
    }

    public function view(AuthUser $authUser, Personnel $personnel): bool
    {
        return $authUser->can('View:Personnel');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Personnel');
    }

    public function update(AuthUser $authUser, Personnel $personnel): bool
    {
        return $authUser->can('Update:Personnel');
    }

    public function delete(AuthUser $authUser, Personnel $personnel): bool
    {
        return $authUser->can('Delete:Personnel');
    }

    public function restore(AuthUser $authUser, Personnel $personnel): bool
    {
        return $authUser->can('Restore:Personnel');
    }

    public function forceDelete(AuthUser $authUser, Personnel $personnel): bool
    {
        return $authUser->can('ForceDelete:Personnel');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Personnel');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Personnel');
    }

    public function replicate(AuthUser $authUser, Personnel $personnel): bool
    {
        return $authUser->can('Replicate:Personnel');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Personnel');
    }

}