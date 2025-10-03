<?php

declare(strict_types=1);

namespace App\Policies\Location;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Location\RackInspection;
use Illuminate\Auth\Access\HandlesAuthorization;

class RackInspectionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RackInspection');
    }

    public function view(AuthUser $authUser, RackInspection $rackInspection): bool
    {
        return $authUser->can('View:RackInspection');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RackInspection');
    }

    public function update(AuthUser $authUser, RackInspection $rackInspection): bool
    {
        return $authUser->can('Update:RackInspection');
    }

    public function delete(AuthUser $authUser, RackInspection $rackInspection): bool
    {
        return $authUser->can('Delete:RackInspection');
    }

    public function restore(AuthUser $authUser, RackInspection $rackInspection): bool
    {
        return $authUser->can('Restore:RackInspection');
    }

    public function forceDelete(AuthUser $authUser, RackInspection $rackInspection): bool
    {
        return $authUser->can('ForceDelete:RackInspection');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RackInspection');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RackInspection');
    }

    public function replicate(AuthUser $authUser, RackInspection $rackInspection): bool
    {
        return $authUser->can('Replicate:RackInspection');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RackInspection');
    }

}