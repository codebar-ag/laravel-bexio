<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Currencies\CreateCurrencyDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Currencies\CreateCurrencyRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CreateCurrencyRequest::class => MockResponse::fixture('Currencies/create-a-currency'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CreateCurrencyRequest(
        new CreateCurrencyDTO(
            name: 'JPY',
            round_factor: 0.05,
        )
    ));

    Saloon::assertSent(CreateCurrencyRequest::class);
});
