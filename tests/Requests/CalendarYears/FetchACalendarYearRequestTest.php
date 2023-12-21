<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\CalendarYears\FetchACalendarYearRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchACalendarYearRequest::class => MockResponse::fixture('CalendarYears/fetch-a-calendar-year'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchACalendarYearRequest(id: 1));

    $mockClient->assertSent(FetchACalendarYearRequest::class);
});
