<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Currencies\FetchAllPossibleCurrencyCodesRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAllPossibleCurrencyCodesRequest::class => MockResponse::fixture('Currencies/fetch-all-possible-currency-codes'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAllPossibleCurrencyCodesRequest);

    $mockClient->assertSent(FetchAllPossibleCurrencyCodesRequest::class);
});
