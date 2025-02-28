<?php

namespace CodebarAg\Bexio\Enums\CalendarYears;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self AGREED()
 * @method static self COLLECTED()
 */
final class VatAccountingTypeEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'AGREED' => 'agreed',
            'COLLECTED' => 'collected',
        ];
    }

    protected static function labels(): array
    {
        return [
            'AGREED' => 'Agreed',
            'COLLECTED' => 'Collected',
        ];
    }
}
