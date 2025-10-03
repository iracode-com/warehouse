<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Rule;
use Illuminate\Auth\Access\HandlesAuthorization;

class RulePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Rule');
    }

    public function view(AuthUser $authUser, Rule $rule): bool
    {
        return $authUser->can('View:Rule');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Rule');
    }

    public function update(AuthUser $authUser, Rule $rule): bool
    {
        return $authUser->can('Update:Rule');
    }

    public function delete(AuthUser $authUser, Rule $rule): bool
    {
        return $authUser->can('Delete:Rule');
    }

    public function restore(AuthUser $authUser, Rule $rule): bool
    {
        return $authUser->can('Restore:Rule');
    }

    public function forceDelete(AuthUser $authUser, Rule $rule): bool
    {
        return $authUser->can('ForceDelete:Rule');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Rule');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Rule');
    }

    public function replicate(AuthUser $authUser, Rule $rule): bool
    {
        return $authUser->can('Replicate:Rule');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Rule');
    }

}