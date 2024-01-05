<?php

namespace CodebarAg\Bexio\Enums\Contacts;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ACTIVE()
 * @method static self INACTIVE()
 */
final class OrderByEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'ID' => 'id',
            'ID_ASC' => 'id_asc',
            'ID_DESC' => 'id_desc',
            'NR' => 'nr',
            'NR_ASC' => 'nr_asc',
            'NR_DESC' => 'nr_desc',
            'NAME_1' => 'name_1',
            'NAME_1_ASC' => 'name_1_asc',
            'NAME_1_DESC' => 'name_1_desc',
            'UPDATED_AT' => 'updated_at',
            'UPDATED_AT_ASC' => 'updated_at_asc',
            'UPDATED_AT_DESC' => 'updated_at_desc',
        ];
    }

    protected static function labels()
    {
        return [
            'ID' => 'Id',
            'ID_ASC' => 'Id Ascending',
            'ID_DESC' => 'Id Descending',
            'NR' => 'Nr',
            'NR_ASC' => 'Nr Ascending',
            'NR_DESC' => 'Nr Descending',
            'NAME_1' => 'Name 1',
            'NAME_1_ASC' => 'Name 1 Ascending',
            'NAME_1_DESC' => 'Name 1 Descending',
            'UPDATED_AT' => 'Updated At',
            'UPDATED_AT_ASC' => 'Updated At Ascending',
            'UPDATED_AT_DESC' => 'Updated At Descending',
        ];
    }
}
