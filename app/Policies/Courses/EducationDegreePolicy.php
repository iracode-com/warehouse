<?php

declare(strict_types=1);

namespace App\Policies\Courses;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Courses\EducationDegree;
use Illuminate\Auth\Access\HandlesAuthorization;

class EducationDegreePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EducationDegree');
    }

    public function view(AuthUser $authUser, EducationDegree $educationDegree): bool
    {
        return $authUser->can('View:EducationDegree');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EducationDegree');
    }

    public function update(AuthUser $authUser, EducationDegree $educationDegree): bool
    {
        return $authUser->can('Update:EducationDegree');
    }

    public function delete(AuthUser $authUser, EducationDegree $educationDegree): bool
    {
        return $authUser->can('Delete:EducationDegree');
    }

    public function restore(AuthUser $authUser, EducationDegree $educationDegree): bool
    {
        return $authUser->can('Restore:EducationDegree');
    }

    public function forceDelete(AuthUser $authUser, EducationDegree $educationDegree): bool
    {
        return $authUser->can('ForceDelete:EducationDegree');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EducationDegree');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EducationDegree');
    }

    public function replicate(AuthUser $authUser, EducationDegree $educationDegree): bool
    {
        return $authUser->can('Replicate:EducationDegree');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EducationDegree');
    }

}