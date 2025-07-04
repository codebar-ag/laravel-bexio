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
