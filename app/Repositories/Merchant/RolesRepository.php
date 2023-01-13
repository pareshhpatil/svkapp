<?php

namespace App\Repositories\Merchant;

use App\Libraries\Encrypt;
use App\Model\Merchant\SubUser\Role;
use App\Repositories\DBRepository;
use Illuminate\Support\Facades\Session;

class RolesRepository extends DBRepository
{
    protected $model;
    protected $merchantID;

    public function __construct(Role $model)
    {
        $this->model = $model;
        $this->merchantID = Encrypt::decode(Session::get('merchant_id'));
    }
}