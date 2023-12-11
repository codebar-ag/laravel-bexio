<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Taxes\DeleteATaxRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        DeleteATaxRequest::class => MockResponse::fixture('Taxes/delete-a-tax'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new DeleteATaxRequest(id: null));

    $mockClient->assertSent(DeleteATaxRequest::class);
})->skip('WAITING FOR SETUP IN DEV ENVIRONMENT')->todo();
