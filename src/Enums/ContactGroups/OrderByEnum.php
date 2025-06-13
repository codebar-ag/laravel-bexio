<?php

namespace CodebarAg\Bexio\Enums\ContactGroups;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ID()
 * @method static self ID_ASC()
 * @method static self ID_DESC()
 * @method static self NAME()
 * @method static self NAME_ASC()
 * @method static self NAME_DESC()
 */
final class OrderByEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'ID' => 'id',
            'ID_ASC' => 'id_asc',
            'ID_DESC' => 'id_desc',
            'NAME' => 'name',
            'NAME_ASC' => 'name_asc',
            'NAME_DESC' => 'name_desc',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ID' => 'Id',
            'ID_ASC' => 'Id Ascending',
            'ID_DESC' => 'Id Descending',
            'NAME' => 'Name',
            'NAME_ASC' => 'Name Ascending',
            'NAME_DESC' => 'Name Descending',
        ];
    }
}
