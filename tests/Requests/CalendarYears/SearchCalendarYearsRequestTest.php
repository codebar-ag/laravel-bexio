<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\CalendarYears\SearchCalendarYearsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        SearchCalendarYearsRequest::class => MockResponse::fixture('CalendarYears/search-calendar-years'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new SearchCalendarYearsRequest(
        searchField: 'start',
        searchTerm: '2022-01-01',
    ));

    Saloon::assertSent(SearchCalendarYearsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(1);
});
