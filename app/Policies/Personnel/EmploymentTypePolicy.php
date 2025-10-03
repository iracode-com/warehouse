<?php

declare(strict_types=1);

namespace App\Policies\Personnel;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Personnel\EmploymentType;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmploymentTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EmploymentType');
    }

    public function view(AuthUser $authUser, EmploymentType $employmentType): bool
    {
        return $authUser->can('View:EmploymentType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EmploymentType');
    }

    public function update(AuthUser $authUser, EmploymentType $employmentType): bool
    {
        return $authUser->can('Update:EmploymentType');
    }

    public function delete(AuthUser $authUser, EmploymentType $employmentType): bool
    {
        return $authUser->can('Delete:EmploymentType');
    }

    public function restore(AuthUser $authUser, EmploymentType $employmentType): bool
    {
        return $authUser->can('Restore:EmploymentType');
    }

    public function forceDelete(AuthUser $authUser, EmploymentType $employmentType): bool
    {
        return $authUser->can('ForceDelete:EmploymentType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EmploymentType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EmploymentType');
    }

    public function replicate(AuthUser $authUser, EmploymentType $employmentType): bool
    {
        return $authUser->can('Replicate:EmploymentType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EmploymentType');
    }

}