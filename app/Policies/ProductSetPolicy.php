<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProductSet;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductSetPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProductSet');
    }

    public function view(AuthUser $authUser, ProductSet $productSet): bool
    {
        return $authUser->can('View:ProductSet');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProductSet');
    }

    public function update(AuthUser $authUser, ProductSet $productSet): bool
    {
        return $authUser->can('Update:ProductSet');
    }

    public function delete(AuthUser $authUser, ProductSet $productSet): bool
    {
        return $authUser->can('Delete:ProductSet');
    }

    public function restore(AuthUser $authUser, ProductSet $productSet): bool
    {
        return $authUser->can('Restore:ProductSet');
    }

    public function forceDelete(AuthUser $authUser, ProductSet $productSet): bool
    {
        return $authUser->can('ForceDelete:ProductSet');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProductSet');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProductSet');
    }

    public function replicate(AuthUser $authUser, ProductSet $productSet): bool
    {
        return $authUser->can('Replicate:ProductSet');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProductSet');
    }

}