<?php

namespace CodebarAg\Bexio\Enums\QrPayments;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self FEE_PAID_BY_SENDER()
 * @method static self FEE_PAID_BY_RECIPIENT()
 * @method static self FEE_SPLIT()
 * @method static self NO_FEE()
 */
final class AllowanceTypeEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'FEE_PAID_BY_SENDER' => 'fee_paid_by_sender',
            'FEE_PAID_BY_RECIPIENT' => 'fee_paid_by_recipient',
            'FEE_SPLIT' => 'fee_split',
            'NO_FEE' => 'no_fee',
        ];
    }

    protected static function labels()
    {
        return [
            'FEE_PAID_BY_SENDER' => 'Fee Paid By Sender',
            'FEE_PAID_BY_RECIPIENT' => 'Fee Paid By Recipient',
            'FEE_SPLIT' => 'Fee Split',
            'NO_FEE' => 'No Fee',
        ];
    }
}
