<?php

namespace CodebarAg\Bexio\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self EQUALS()
 * @method static self DOUBLE_EQUALS()
 * @method static self EQUAL()
 * @method static self NOT_EQUALS()
 * @method static self GREATER_THAN_SYMBOL()
 * @method static self GREATER_THAN()
 * @method static self GREATER_EQUAL_SYMBOL()
 * @method static self GREATER_EQUAL()
 * @method static self LESS_THAN_SYMBOL()
 * @method static self LESS_THAN()
 * @method static self LESS_EQUAL_SYMBOL()
 * @method static self LESS_EQUAL()
 * @method static self LIKE()
 * @method static self NOT_LIKE()
 * @method static self IS_NULL()
 * @method static self NOT_NULL()
 * @method static self IN()
 * @method static self NOT_IN()
 */
final class SearchCriteriaEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'EQUALS' => '=',
            'DOUBLE_EQUALS' => '==',
            'EQUAL' => 'equal',
            'NOT_EQUALS' => '!=',
            'GREATER_THAN_SYMBOL' => '>',
            'GREATER_THAN' => 'greater_than',
            'GREATER_EQUAL_SYMBOL' => '>=',
            'GREATER_EQUAL' => 'greater_equal',
            'LESS_THAN_SYMBOL' => '<',
            'LESS_THAN' => 'less_than',
            'LESS_EQUAL_SYMBOL' => '<=',
            'LESS_EQUAL' => 'less_equal',
            'LIKE' => 'like',
            'NOT_LIKE' => 'not_like',
            'IS_NULL' => 'is_null',
            'NOT_NULL' => 'not_null',
            'IN' => 'in',
            'NOT_IN' => 'not_in',
        ];
    }
}
