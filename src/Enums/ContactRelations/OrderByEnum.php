<?php

namespace CodebarAg\Bexio\Enums\ContactRelations;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ID()
 * @method static self ID_ASC()
 * @method static self ID_DESC()
 * @method static self CONTACT_ID()
 * @method static self CONTACT_ID_ASC()
 * @method static self CONTACT_ID_DESC()
 * @method static self CONTACT_SUB_ID()
 * @method static self CONTACT_SUB_ID_ASC()
 * @method static self CONTACT_SUB_ID_DESC()
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
            'CONTACT_ID' => 'contact_id',
            'CONTACT_ID_ASC' => 'contact_id_asc',
            'CONTACT_ID_DESC' => 'contact_id_desc',
            'CONTACT_SUB_ID' => 'contact_sub_id',
            'CONTACT_SUB_ID_ASC' => 'contact_sub_id_asc',
            'CONTACT_SUB_ID_DESC' => 'contact_sub_id_desc',
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
            'CONTACT_ID' => 'Contact Id',
            'CONTACT_ID_ASC' => 'Contact Id Ascending',
            'CONTACT_ID_DESC' => 'Contact Id Descending',
            'CONTACT_SUB_ID' => 'Contact Sub Id',
            'CONTACT_SUB_ID_ASC' => 'Contact Sub Id Ascending',
            'CONTACT_SUB_ID_DESC' => 'Contact Sub Id Descending',
            'UPDATED_AT' => 'Updated At',
            'UPDATED_AT_ASC' => 'Updated At Ascending',
            'UPDATED_AT_DESC' => 'Updated At Descending',
        ];
    }
}
