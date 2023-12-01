<?php

use CodebarAg\Zendesk\Tests\TestCase;
use Illuminate\Support\Facades\Event;

uses(TestCase::class)
    ->beforeEach(function () {
        Event::fake();
    })
    ->in(__DIR__);
