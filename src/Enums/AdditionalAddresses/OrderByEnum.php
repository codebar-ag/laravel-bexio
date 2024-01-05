<?php

namespace CodebarAg\Bexio\Enums\AdditionalAddresses;

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
            'POSTCODE' => 'postcode',
            'POSTCODE_ASC' => 'postcode_asc',
            'POSTCODE_DESC' => 'postcode_desc',
            'COUNTRY_ID' => 'country_id',
            'COUNTRY_ID_ASC' => 'country_id_asc',
            'COUNTRY_ID_DESC' => 'country_id_desc',
        ];
    }

    protected static function labels()
    {
        return [
            'ID' => 'Id',
            'ID_ASC' => 'Id Ascending',
            'ID_DESC' => 'Id Descending',
            'NAME' => 'Name',
            'NAME_ASC' => 'Name Ascending',
            'NAME_DESC' => 'Name Descending',
            'POSTCODE' => 'Postcode',
            'POSTCODE_ASC' => 'Postcode Ascending',
            'POSTCODE_DESC' => 'Postcode Descending',
            'COUNTRY_ID' => 'Country Id',
            'COUNTRY_ID_ASC' => 'Country Id Ascending',
            'COUNTRY_ID_DESC' => 'Country Id Descending',
        ];
    }
}
