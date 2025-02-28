<?php

namespace CodebarAg\Bexio\Enums\CalendarYears;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self EFFECTIVE()
 * @method static self NET_TAX()
 */
final class VatAccountingMethodEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'EFFECTIVE' => 'effective',
            'NET_TAX' => 'net_tax',
        ];
    }

    protected static function labels(): array
    {
        return [
            'EFFECTIVE' => 'Effective',
            'NET_TAX' => 'Net Tax',
        ];
    }
}
