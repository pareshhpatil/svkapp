<?php

namespace App\Repositories\Merchant;

use App\Model\CostType;
use App\Repositories\DBRepository;

class CostTypeRepository extends DBRepository
{
    protected $model;

    public function __construct(CostType $model)
    {
        $this->model = $model;
    }
}