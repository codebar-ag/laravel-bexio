<?php

namespace CodebarAg\Bexio\Enums\Accounts;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self EARNINGS()
 * @method static self EXPENDITURES()
 * @method static self ACTIVE_ACCOUNTS()
 * @method static self PASSIVE_ACCOUNTS()
 * @method static self COMPLETE_ACCOUNTS()
 */
final class AccountTypeEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'EARNINGS' => 1,
            'EXPENDITURES' => 2,
            'ACTIVE_ACCOUNTS' => 3,
            'PASSIVE_ACCOUNTS' => 4,
            'COMPLETE_ACCOUNTS' => 5,
        ];
    }

    protected static function labels(): array
    {
        return [
            'EARNINGS' => 'Earnings',
            'EXPENDITURES' => 'Expenditures',
            'ACTIVE_ACCOUNTS' => 'Active Accounts',
            'PASSIVE_ACCOUNTS' => 'Passive Accounts',
            'COMPLETE_ACCOUNTS' => 'Complete Accounts (Diploma)',
        ];
    }
}
