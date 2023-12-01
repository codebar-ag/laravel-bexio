<?php

namespace CodebarAg\Zendesk\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CodebarAg\Zendesk\Zendesk
 */
class Zendesk extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \CodebarAg\Zendesk\Zendesk::class;
    }
}
