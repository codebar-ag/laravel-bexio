<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Currencies\FetchAllPossibleCurrencyCodesRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAllPossibleCurrencyCodesRequest::class => MockResponse::fixture('Currencies/fetch-all-possible-currency-codes'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAllPossibleCurrencyCodesRequest);

    Saloon::assertSent(FetchAllPossibleCurrencyCodesRequest::class);
})->group('currencies');
