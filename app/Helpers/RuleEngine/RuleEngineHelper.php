<?php

namespace App\Helpers\RuleEngine;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class RuleEngineHelper
{
    protected $queryName;
    protected $queryOperator;
    protected $queryValue;

    public function setParameters(
        $queryName,
        $queryOperator,
        $queryValue
    ) {
        $this->queryName = $queryName;
        $this->queryOperator = $queryOperator;
        $this->queryValue = $queryValue;
    }

    public function resolveOperator()
    {
        switch ($this->queryOperator) {
            case 'eq':
            case 'et':
                $result = '=';
                break;
            case 'neq':
            case 'net':
                $result = '<>';
                break;
            case 'is_at_least':
            case 'more-than':
            case 'gt':
                $result = '>';
                break;
            case 'gte':
                $result = '>=';
                break;
            case 'less-than':
            case 'lt':
                $result = '<';
                break;
            default:
                $result = true;
        }

        return $result;
    }


    /**
     * @param string $table
     *
     * @return array
     */
    public function entityResolver($table, $type, $typeID)
    {
        /** @var Builder $Builder */
        $Builder = DB::table($table)
                    ->where('is_active', 1);

        if($typeID != 'all') {
            $Builder = $Builder->where($type, $typeID);
        }

        switch ($this->queryOperator) {
            case 'et':
            case 'eq':
                $Builder = $Builder->where($this->queryName, $this->queryValue);
                break;
            case 'net':
            case 'neq':
            case 'gte':
            case 'lt':
            case 'lte':
            case 'gt':
                $Builder = $Builder->where($this->queryName, $this->resolveOperator(), $this->queryValue);
                break;
        }

        return $Builder->pluck($type)->toArray();
    }
}

