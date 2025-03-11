<?php

namespace CodebarAg\Bexio\Enums\Taxes;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self SALES_TAX()
 * @method static self PRE_TAX()
 */
final class TypesEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'SALES_TAX' => 'sales_tax',
            'PRE_TAX' => 'pre_tax',
        ];
    }

    protected static function labels(): array
    {
        return [
            'SALES_TAX' => 'Sales Tax',
            'PRE_TAX' => 'Pre Tax',
        ];
    }
}
