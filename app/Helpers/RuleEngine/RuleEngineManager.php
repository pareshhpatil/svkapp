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
        $invoiceIDsCollection = [];
        foreach ($this->queries as $query) {
            $queryName           = 'grand_total';
            $queryOperator       = Arr::get($query, 'query_operator');
            $queryValue          = Arr::get($query, 'query_value');

            $RuleEngineHelper->setParameters($queryName, $queryOperator, $queryValue);

            $invoiceIDsCollection[] = $RuleEngineHelper->entityResolver('payment_request', $this->type, $this->typeID);
        }

        return array_unique(array_merge(...$invoiceIDsCollection));
    }
}