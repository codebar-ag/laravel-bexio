<?php

namespace CodebarAg\Bexio\Dto\SubPositions;

use Spatie\LaravelData\Data;

class CreateEditSubPositionDTO extends Data
{
    public function __construct(
        public string $text,
        public bool $show_pos_nr,
    ) {}
}
