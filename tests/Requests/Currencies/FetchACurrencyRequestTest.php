<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Currencies\FetchACurrencyRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchACurrencyRequest::class => MockResponse::fixture('Currencies/fetch-a-currency'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchACurrencyRequest(id: 1));

    Saloon::assertSent(FetchACurrencyRequest::class);
});
