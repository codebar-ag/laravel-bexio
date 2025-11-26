<?php

namespace CodebarAg\Bexio\Enums\Items;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self ID()
 * @method static self ID_ASC()
 * @method static self ID_DESC()
 * @method static self INTERN_NAME()
 * @method static self INTERN_NAME_ASC()
 * @method static self INTERN_NAME_DESC()
 */
final class ItemsOrderByEnum extends Enum
{
    protected static function values(): array
    {
        return [
            'ID' => 'id',
            'ID_ASC' => 'id_asc',
            'ID_DESC' => 'id_desc',
            'INTERN_NAME' => 'intern_name',
            'INTERN_NAME_ASC' => 'intern_name_asc',
            'INTERN_NAME_DESC' => 'intern_name_desc',
        ];
    }

    protected static function labels(): array
    {
        return [
            'ID' => 'Id',
            'ID_ASC' => 'Id Ascending',
            'ID_DESC' => 'Id Descending',
            'INTERN_NAME' => 'Intern Name',
            'INTERN_NAME_ASC' => 'Intern Name Ascending',
            'INTERN_NAME_DESC' => 'Intern Name Descending',
        ];
    }
}
