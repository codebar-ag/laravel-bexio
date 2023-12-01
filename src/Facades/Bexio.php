<?php

namespace CodebarAg\Bexio\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CodebarAg\Bexio\Bexio
 */
class Bexio extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \CodebarAg\Bexio\Bexio::class;
    }
}
