<?php

declare(strict_types=1);

namespace App\Policies\Location;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Location\Rack;
use Illuminate\Auth\Access\HandlesAuthorization;

class RackPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Rack');
    }

    public function view(AuthUser $authUser, Rack $rack): bool
    {
        return $authUser->can('View:Rack');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Rack');
    }

    public function update(AuthUser $authUser, Rack $rack): bool
    {
        return $authUser->can('Update:Rack');
    }

    public function delete(AuthUser $authUser, Rack $rack): bool
    {
        return $authUser->can('Delete:Rack');
    }

    public function restore(AuthUser $authUser, Rack $rack): bool
    {
        return $authUser->can('Restore:Rack');
    }

    public function forceDelete(AuthUser $authUser, Rack $rack): bool
    {
        return $authUser->can('ForceDelete:Rack');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Rack');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Rack');
    }

    public function replicate(AuthUser $authUser, Rack $rack): bool
    {
        return $authUser->can('Replicate:Rack');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Rack');
    }

}