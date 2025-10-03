<?php

declare(strict_types=1);

namespace App\Policies\Personnel;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Personnel\CooperationType;
use Illuminate\Auth\Access\HandlesAuthorization;

class CooperationTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CooperationType');
    }

    public function view(AuthUser $authUser, CooperationType $cooperationType): bool
    {
        return $authUser->can('View:CooperationType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CooperationType');
    }

    public function update(AuthUser $authUser, CooperationType $cooperationType): bool
    {
        return $authUser->can('Update:CooperationType');
    }

    public function delete(AuthUser $authUser, CooperationType $cooperationType): bool
    {
        return $authUser->can('Delete:CooperationType');
    }

    public function restore(AuthUser $authUser, CooperationType $cooperationType): bool
    {
        return $authUser->can('Restore:CooperationType');
    }

    public function forceDelete(AuthUser $authUser, CooperationType $cooperationType): bool
    {
        return $authUser->can('ForceDelete:CooperationType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CooperationType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CooperationType');
    }

    public function replicate(AuthUser $authUser, CooperationType $cooperationType): bool
    {
        return $authUser->can('Replicate:CooperationType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CooperationType');
    }

}