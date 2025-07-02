<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Currencies\EditCurrencyDTO;
use CodebarAg\Bexio\Requests\Currencies\EditACurrencyRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        EditACurrencyRequest::class => MockResponse::fixture('Currencies/edit-a-currency'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new EditACurrencyRequest(
        8,
        new EditCurrencyDTO(
            round_factor: 0.01,
        )
    ));

    $mockClient->assertSent(EditACurrencyRequest::class);
});
