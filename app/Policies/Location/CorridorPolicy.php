<?php

declare(strict_types=1);

namespace App\Policies\Location;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Location\Corridor;
use Illuminate\Auth\Access\HandlesAuthorization;

class CorridorPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Corridor');
    }

    public function view(AuthUser $authUser, Corridor $corridor): bool
    {
        return $authUser->can('View:Corridor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Corridor');
    }

    public function update(AuthUser $authUser, Corridor $corridor): bool
    {
        return $authUser->can('Update:Corridor');
    }

    public function delete(AuthUser $authUser, Corridor $corridor): bool
    {
        return $authUser->can('Delete:Corridor');
    }

    public function restore(AuthUser $authUser, Corridor $corridor): bool
    {
        return $authUser->can('Restore:Corridor');
    }

    public function forceDelete(AuthUser $authUser, Corridor $corridor): bool
    {
        return $authUser->can('ForceDelete:Corridor');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Corridor');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Corridor');
    }

    public function replicate(AuthUser $authUser, Corridor $corridor): bool
    {
        return $authUser->can('Replicate:Corridor');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Corridor');
    }

}