<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\CalendarYears\FetchACalendarYearRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchACalendarYearRequest::class => MockResponse::fixture('CalendarYears/fetch-a-calendar-year'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchACalendarYearRequest(id: 1));

    Saloon::assertSent(FetchACalendarYearRequest::class);
});
