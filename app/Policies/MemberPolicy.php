<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;

class MemberPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        return $admin->hasPermission('view_member');
    }

    /**
     * Determine whether the user can view the model.
     */
    //phuong thuc show
    public function view(Admin $admin): bool 
    {
        return $admin->hasPermission('show_member');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
      return $admin->hasPermission('add_member');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin): bool
    {
      return $admin->hasPermission('update_member');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin): bool
    {
       return $admin->hasPermission('delete_member');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Admin $admin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Admin $admin): bool
    {
        return false;
    }
}
