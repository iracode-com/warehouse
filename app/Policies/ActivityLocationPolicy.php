<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ActivityLocation;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityLocationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ActivityLocation');
    }

    public function view(AuthUser $authUser, ActivityLocation $activityLocation): bool
    {
        return $authUser->can('View:ActivityLocation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ActivityLocation');
    }

    public function update(AuthUser $authUser, ActivityLocation $activityLocation): bool
    {
        return $authUser->can('Update:ActivityLocation');
    }

    public function delete(AuthUser $authUser, ActivityLocation $activityLocation): bool
    {
        return $authUser->can('Delete:ActivityLocation');
    }

    public function restore(AuthUser $authUser, ActivityLocation $activityLocation): bool
    {
        return $authUser->can('Restore:ActivityLocation');
    }

    public function forceDelete(AuthUser $authUser, ActivityLocation $activityLocation): bool
    {
        return $authUser->can('ForceDelete:ActivityLocation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ActivityLocation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ActivityLocation');
    }

    public function replicate(AuthUser $authUser, ActivityLocation $activityLocation): bool
    {
        return $authUser->can('Replicate:ActivityLocation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ActivityLocation');
    }

}