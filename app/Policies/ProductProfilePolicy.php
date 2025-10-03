<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ProductProfile;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductProfilePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProductProfile');
    }

    public function view(AuthUser $authUser, ProductProfile $productProfile): bool
    {
        return $authUser->can('View:ProductProfile');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProductProfile');
    }

    public function update(AuthUser $authUser, ProductProfile $productProfile): bool
    {
        return $authUser->can('Update:ProductProfile');
    }

    public function delete(AuthUser $authUser, ProductProfile $productProfile): bool
    {
        return $authUser->can('Delete:ProductProfile');
    }

    public function restore(AuthUser $authUser, ProductProfile $productProfile): bool
    {
        return $authUser->can('Restore:ProductProfile');
    }

    public function forceDelete(AuthUser $authUser, ProductProfile $productProfile): bool
    {
        return $authUser->can('ForceDelete:ProductProfile');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProductProfile');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProductProfile');
    }

    public function replicate(AuthUser $authUser, ProductProfile $productProfile): bool
    {
        return $authUser->can('Replicate:ProductProfile');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProductProfile');
    }

}