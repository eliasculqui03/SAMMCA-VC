<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Conductividad;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConductividadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_conductividad');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Conductividad $conductividad): bool
    {
        return $user->can('view_conductividad');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_conductividad');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Conductividad $conductividad): bool
    {
        return $user->can('update_conductividad');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Conductividad $conductividad): bool
    {
        return $user->can('delete_conductividad');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_conductividad');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Conductividad $conductividad): bool
    {
        return $user->can('force_delete_conductividad');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_conductividad');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Conductividad $conductividad): bool
    {
        return $user->can('restore_conductividad');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_conductividad');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Conductividad $conductividad): bool
    {
        return $user->can('replicate_conductividad');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_conductividad');
    }
}
