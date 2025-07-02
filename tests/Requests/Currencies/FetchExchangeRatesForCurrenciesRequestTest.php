<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Currencies\FetchExchangeRatesForCurrenciesRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchExchangeRatesForCurrenciesRequest::class => MockResponse::fixture('Currencies/fetch-exchange-rates-for-currencies'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchExchangeRatesForCurrenciesRequest(id: 2));

    $mockClient->assertSent(FetchExchangeRatesForCurrenciesRequest::class);
});
