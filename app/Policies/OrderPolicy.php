<?php

namespace App\Policies;

use App\Model\Order;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // if current user is admin
        if(!empty($user->role()) && $user->role()->role_name == 'Admin') {
           return true;
        }

        return $user->hasPermission('create-change-order');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Order $Order)
    {
        unset($Order);
        // if current user is admin
        if(!empty($user->role()) && $user->role()->role_name == 'Admin') {
            return true;
        }
        return $user->hasPermission('create-change-order');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // if current user is admin
        if(!empty($user->role()) && $user->role()->role_name == 'Admin') {
            return true;
        }
        return $user->hasPermission('create-change-order');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        // if current user is admin
        if(!empty($user->role()) && $user->role()->role_name == 'Admin') {
            return true;
        }
        return $user->hasPermission('approve-change-order');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        // if current user is admin
        if(!empty($user->role()) && $user->role()->role_name == 'Admin') {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Order $order)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Order $order)
    {
        // if current user is admin
        if(!empty($user->role()) && $user->role()->role_name == 'Admin') {
            return true;
        }
        return false;
    }
}
