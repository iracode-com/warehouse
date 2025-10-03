<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CategoryAttribute;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryAttributePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CategoryAttribute');
    }

    public function view(AuthUser $authUser, CategoryAttribute $categoryAttribute): bool
    {
        return $authUser->can('View:CategoryAttribute');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CategoryAttribute');
    }

    public function update(AuthUser $authUser, CategoryAttribute $categoryAttribute): bool
    {
        return $authUser->can('Update:CategoryAttribute');
    }

    public function delete(AuthUser $authUser, CategoryAttribute $categoryAttribute): bool
    {
        return $authUser->can('Delete:CategoryAttribute');
    }

    public function restore(AuthUser $authUser, CategoryAttribute $categoryAttribute): bool
    {
        return $authUser->can('Restore:CategoryAttribute');
    }

    public function forceDelete(AuthUser $authUser, CategoryAttribute $categoryAttribute): bool
    {
        return $authUser->can('ForceDelete:CategoryAttribute');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CategoryAttribute');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CategoryAttribute');
    }

    public function replicate(AuthUser $authUser, CategoryAttribute $categoryAttribute): bool
    {
        return $authUser->can('Replicate:CategoryAttribute');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CategoryAttribute');
    }

}