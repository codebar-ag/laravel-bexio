<?php

namespace CodebarAg\Bexio\Enums\Contacts;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ID()
 * @method static self ID_ASC()
 * @method static self ID_DESC()
 * @method static self NR()
 * @method static self NR_ASC()
 * @method static self NR_DESC()
 * @method static self NAME_1()
 * @method static self NAME_1_ASC()
 * @method static self NAME_1_DESC()
 * @method static self UPDATED_AT()
 * @method static self UPDATED_AT_ASC()
 * @method static self UPDATED_AT_DESC()
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

    protected static function labels(): array
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
