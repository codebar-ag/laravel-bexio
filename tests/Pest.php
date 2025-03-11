<?php

use CodebarAg\Bexio\Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Saloon\Http\Faking\MockClient;

uses(TestCase::class)
    ->beforeEach(function () {
        Event::fake();
        MockClient::destroyGlobal();
    })
    ->in(__DIR__);
