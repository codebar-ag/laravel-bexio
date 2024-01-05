<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\CalendarYears\FetchAListOfCalendarYearsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfCalendarYearsRequest::class => MockResponse::fixture('CalendarYears/fetch-a-list-of-calendar-years'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfCalendarYearsRequest());

    $mockClient->assertSent(FetchAListOfCalendarYearsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(8);
});
