<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\BusinessYears\FetchABusinessYearRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchABusinessYearRequest::class => MockResponse::fixture('BusinessYears/fetch-a-business-year'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchABusinessYearRequest(id: 1));

    Saloon::assertSent(FetchABusinessYearRequest::class);
});
