<?php

namespace App\Constants\Models;

/**
 * Interface IModel
 *
 * @package App\Constants\Models
 */
interface IModel
{
    const  EQUALS_TO              = '=';
    const  NOT_EQUALS_TO          = '!=';
    const  LESS_THAN              = '<';
    const  LESS_THAN_EQUALS_TO    = '<=';
    const  GREATER_THAN           = '>';
    const  GREATER_THAN_EQUALS_TO = '>=';
    const  LIKE                   = 'LIKE';
    const  NOT_LIKE               = 'NOT LIKE';

    const DOT   = '.';
    const COMMA = ',';
    const COLON = ':';

    const ORDER_ASC  = 'ASC';
    const ORDER_DESC = 'DESC';

}

