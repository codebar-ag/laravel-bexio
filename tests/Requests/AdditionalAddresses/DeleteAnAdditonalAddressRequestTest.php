<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\AdditionalAddresses\DeleteAnAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        DeleteAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/delete-an-additional-address'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new DeleteAnAdditionalAddressRequest(
        contactId: 1,
        id: 10
    ));

    $mockClient->assertSent(DeleteAnAdditionalAddressRequest::class);
});
