<?php

namespace App\Policies;

use App\Libraries\Encrypt;
use App\Model\Invoice;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Session;

class InvoicePolicy
{
    use HandlesAuthorization;

    private $merchantID;

    public function __construct()
    {
        $this->merchantID = Encrypt::decode(Session::get('merchant_id'));
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // if current user is admin
        if(!empty($user->role($this->merchantID)) && $user->role($this->merchantID)->name == 'Admin') {
            return true;
        }

        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Invoice $invoice)
    {
        // if current user is admin
        if(!empty($user->role($this->merchantID)) && $user->role($this->merchantID)->name == 'Admin') {
            return true;
        }

        return true;
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
        if(!empty($user->role($this->merchantID)) && $user->role($this->merchantID)->name == 'Admin') {
            return true;
        }

        return $user->hasPermission($this->merchantID,'create-invoice');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Invoice $invoice)
    {
        // if current user is admin
        if(!empty($user->role($this->merchantID)) && $user->role($this->merchantID)->name == 'Admin') {
            return true;
        }

        return $user->hasPermission($this->merchantID,'update-invoice');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Invoice $invoice)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Invoice $invoice)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Invoice $invoice)
    {
        //
    }
}
