<?php

namespace App\Providers;

use App\Model\Contract;
use App\Model\Invoice;
use App\Model\Order;
// use App\Policies\ContractPolicy;
// use App\Policies\InvoicePolicy;
// use App\Policies\OrderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    // protected $policies = [
    //     Order::class      => OrderPolicy::class,
    //     Invoice::class    => InvoicePolicy::class,
    //     Contract::class    => ContractPolicy::class
    // ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //
    }
}
