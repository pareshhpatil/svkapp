<?php

namespace App\Repositories\Merchant;

use App\Libraries\Encrypt;
use App\Model\Merchant\CostType;
use App\Repositories\DBRepository;
use Illuminate\Support\Facades\Session;

class CostTypeRepository extends DBRepository
{
    protected $model;
    protected $merchantID;

    public function __construct(CostType $model)
    {
        $this->model = $model;
        $this->merchantID = Encrypt::decode(Session::get('merchant_id'));
    }
}