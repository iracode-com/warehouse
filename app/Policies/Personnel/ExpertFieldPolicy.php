<?php

declare(strict_types=1);

namespace App\Policies\Personnel;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Personnel\ExpertField;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpertFieldPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ExpertField');
    }

    public function view(AuthUser $authUser, ExpertField $expertField): bool
    {
        return $authUser->can('View:ExpertField');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ExpertField');
    }

    public function update(AuthUser $authUser, ExpertField $expertField): bool
    {
        return $authUser->can('Update:ExpertField');
    }

    public function delete(AuthUser $authUser, ExpertField $expertField): bool
    {
        return $authUser->can('Delete:ExpertField');
    }

    public function restore(AuthUser $authUser, ExpertField $expertField): bool
    {
        return $authUser->can('Restore:ExpertField');
    }

    public function forceDelete(AuthUser $authUser, ExpertField $expertField): bool
    {
        return $authUser->can('ForceDelete:ExpertField');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ExpertField');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ExpertField');
    }

    public function replicate(AuthUser $authUser, ExpertField $expertField): bool
    {
        return $authUser->can('Replicate:ExpertField');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ExpertField');
    }

}