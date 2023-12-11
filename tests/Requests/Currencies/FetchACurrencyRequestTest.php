<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Currencies\FetchACurrencyRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchACurrencyRequest::class => MockResponse::fixture('Currencies/fetch-a-currency'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchACurrencyRequest(id: 1));

    $mockClient->assertSent(FetchACurrencyRequest::class);
});
