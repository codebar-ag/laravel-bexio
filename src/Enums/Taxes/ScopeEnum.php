<?php

namespace CodebarAg\Bexio\Enums\Taxes;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ACTIVE()
 * @method static self INACTIVE()
 */
final class ScopeEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'ACTIVE' => 'active',
            'INACTIVE' => 'inactive',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ACTIVE' => 'Active',
            'INACTIVE' => 'Inactive',
        ];
    }
}
