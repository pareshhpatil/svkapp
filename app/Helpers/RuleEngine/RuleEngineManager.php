<?php

namespace App\Helpers\RuleEngine;

use Illuminate\Support\Arr;

class RuleEngineManager
{
    public $type;
    public $typeID;
    public $queries;

    public function __construct($type, $typeID, $queries)
    {
        $this->type = $type;
        $this->typeID = $typeID;
        $this->queries = $queries;
    }

    /**
     * @return array
     */
    public function run()
    {
        $RuleEngineHelper = new RuleEngineHelper();
        $IDsCollection = [];
        foreach ($this->queries as $query) {
            $queryName           = Arr::get($query, 'query_name');
            $queryModel          = Arr::get($query, 'query_model');
            $queryOperator       = Arr::get($query, 'query_operator');
            $queryValue          = Arr::get($query, 'query_value');

            $RuleEngineHelper->setParameters($queryName, $queryOperator, $queryValue);

            if($queryModel == 'payment_request') {
                $IDsCollection[] = $RuleEngineHelper->entityResolver('payment_request', $this->type, $this->typeID);
            }

            if($queryModel == 'change_order') {
                $IDsCollection[] = $RuleEngineHelper->entityResolver('order', $this->type, $this->typeID);
            }

        }

        return array_unique(array_merge(...$IDsCollection));
    }
}