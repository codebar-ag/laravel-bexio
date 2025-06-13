<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactAdditionalAddresses\CreateEditContactAdditionalAddressDTO;
use CodebarAg\Bexio\Requests\ContactAdditionalAddresses\EditAContactAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        EditAContactAdditionalAddressRequest::class => MockResponse::fixture('ContactAdditionalAddresses/edit-contact-additional-address'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new EditAContactAdditionalAddressRequest(
        1,
        38,
        new CreateEditContactAdditionalAddressDTO(
            name: 'Test Edit',
            subject: 'Test Subject Edit',
            description: 'This is a test edit',
            street_name: 'Test Street Edit',
            house_number: '42B',
            address_addition: 'c/o Test Edit',
            postcode: '4567',
            city: 'Test City Edit',
        )
    ));

    $mockClient->assertSent(EditAContactAdditionalAddressRequest::class);
});
