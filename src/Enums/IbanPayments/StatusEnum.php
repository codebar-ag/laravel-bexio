<?php

namespace CodebarAg\Bexio\Enums\IbanPayments;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self OPEN()
 * @method static self TRANSFERRED()
 * @method static self DOWNLOADED()
 * @method static self ERROR()
 * @method static self CANCELLED()
 */
final class StatusEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'OPEN' => 'open',
            'TRANSFERRED' => 'transferred',
            'DOWNLOADED' => 'downloaded',
            'ERROR' => 'error',
            'CANCELLED' => 'cancelled',
        ];
    }

    protected static function labels(): array
    {
        return [
            'OPEN' => 'Open',
            'TRANSFERRED' => 'Transferred',
            'DOWNLOADED' => 'Downloaded',
            'ERROR' => 'Error',
            'CANCELLED' => 'Cancelled',
        ];
    }
}
