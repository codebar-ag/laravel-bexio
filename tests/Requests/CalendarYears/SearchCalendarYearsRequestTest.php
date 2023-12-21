<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\CalendarYears\SearchCalendarYearsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        SearchCalendarYearsRequest::class => MockResponse::fixture('CalendarYears/search-calendar-years'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SearchCalendarYearsRequest(
        searchField: 'start',
        searchTerm: '2022-01-01',
    ));

    $mockClient->assertSent(SearchCalendarYearsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(1);
});
