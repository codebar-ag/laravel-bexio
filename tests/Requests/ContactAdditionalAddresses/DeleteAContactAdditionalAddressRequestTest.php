<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ContactAdditionalAddresses\DeleteAContactAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        DeleteAContactAdditionalAddressRequest::class => MockResponse::fixture('ContactAdditionalAddresses/delete-a-contact-additional-address'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new DeleteAContactAdditionalAddressRequest(contactId: 1, id: 9));

    $mockClient->assertSent(DeleteAContactAdditionalAddressRequest::class);
});
