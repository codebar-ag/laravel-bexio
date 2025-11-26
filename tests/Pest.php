<?php

use CodebarAg\Bexio\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Saloon\Laravel\Saloon;

uses(TestCase::class)
    ->beforeEach(function () {
        Event::fake();
    })
    ->afterEach(function () {
        Saloon::fake([]);
    })
    ->in(__DIR__);

/**
 * Helper function to check if fixtures should be reset/regenerated.
 * Set RESET_FIXTURES=true in phpunit.xml to regenerate fixtures from live API.
 * Defaults to false (use existing fixtures).
 */
function shouldResetFixtures(): bool
{
    return filter_var(getenv('RESET_FIXTURES') ?: false, FILTER_VALIDATE_BOOLEAN);
}
