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
 * Set RESET_FIXTURES=true in phpunit.xml to regenerate fixtures from the live API.
 * Defaults to false (use existing fixtures).
 *
 * Reads from getenv() and from PHPUnit's <env> bag ($_ENV/$_SERVER), because
 * PHPUnit-defined <env> variables are not always exposed through getenv().
 */
function shouldResetFixtures(): bool
{
    $value = getenv('RESET_FIXTURES');

    if ($value === false || $value === '') {
        $value = $_ENV['RESET_FIXTURES'] ?? $_SERVER['RESET_FIXTURES'] ?? false;
    }

    return filter_var($value, FILTER_VALIDATE_BOOLEAN);
}
