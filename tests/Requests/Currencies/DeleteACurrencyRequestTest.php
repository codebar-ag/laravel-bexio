<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Currencies\DeleteACurrencyRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        DeleteACurrencyRequest::class => MockResponse::fixture('Currencies/delete-a-currency'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new DeleteACurrencyRequest(id: 6));

    $mockClient->assertSent(DeleteACurrencyRequest::class);
});
