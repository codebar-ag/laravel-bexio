<?php

namespace CodebarAg\Bexio\Enums\Countries;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ID()
 * @method static self ID_ASC()
 * @method static self ID_DESC()
 * @method static self NAME()
 * @method static self NAME_ASC()
 * @method static self NAME_DESC()
 * @method static self NAME_SHORT()
 * @method static self NAME_SHORT_ASC()
 * @method static self NAME_SHORT_DESC()
 */
final class CountriesOrderByEnum extends Enum
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
            'NAME_SHORT' => 'name_short',
            'NAME_SHORT_ASC' => 'name_short_asc',
            'NAME_SHORT_DESC' => 'name_short_desc',
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
            'NAME_SHORT' => 'Name Short',
            'NAME_SHORT_ASC' => 'Name Short Ascending',
            'NAME_SHORT_DESC' => 'Name Short Descending',
        ];
    }
}
