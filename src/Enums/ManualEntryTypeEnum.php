<?php

namespace CodebarAg\Bexio\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self MANUAL_SINGLE_ENTRY()
 * @method static self MANUAL_GROUP_ENTRY()
 * @method static self MANUAL_COMPOUND_ENTRY()
 */
final class ManualEntryTypeEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'MANUAL_SINGLE_ENTRY' => 'manual_single_entry',
            'MANUAL_GROUP_ENTRY' => 'manual_group_entry',
            'MANUAL_COMPOUND_ENTRY' => 'manual_compound_entry',
        ];
    }

    protected static function labels()
    {
        return [
            'MANUAL_SINGLE_ENTRY' => 'Manual Single Entry',
            'MANUAL_GROUP_ENTRY' => 'Manual Group Entry',
            'MANUAL_COMPOUND_ENTRY' => 'Manual Compound Entry',
        ];
    }
}
