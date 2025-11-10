<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Currencies\FetchExchangeRatesForCurrenciesRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchExchangeRatesForCurrenciesRequest::class => MockResponse::fixture('Currencies/fetch-exchange-rates-for-currencies'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchExchangeRatesForCurrenciesRequest(id: 2));

    Saloon::assertSent(FetchExchangeRatesForCurrenciesRequest::class);
});
