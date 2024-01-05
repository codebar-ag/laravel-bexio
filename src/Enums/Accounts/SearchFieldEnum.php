<?php

namespace CodebarAg\Bexio\Enums\Accounts;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ACCOUNT_NO()
 * @method static self FIBU_ACCOUNT_GROUP_ID()
 * @method static self NAME()
 * @method static self ACCOUNT_TYPE()
 */
final class SearchFieldEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'ACCOUNT_NO' => 'account_no',
            'FIBU_ACCOUNT_GROUP_ID' => 'fibu_account_group_id',
            'NAME' => 'name',
            'ACCOUNT_TYPE' => 'account_type',
        ];
    }

    protected static function labels()
    {
        return [
            'ACCOUNT_NO' => 'Account No',
            'FIBU_ACCOUNT_GROUP_ID' => 'Fibu Account Group Id',
            'NAME' => 'Name',
            'ACCOUNT_TYPE' => 'Account Type',
        ];
    }
}
