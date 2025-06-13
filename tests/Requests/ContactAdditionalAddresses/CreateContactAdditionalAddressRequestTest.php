<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactAdditionalAddresses\CreateEditContactAdditionalAddressDTO;
use CodebarAg\Bexio\Requests\ContactAdditionalAddresses\CreateContactAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        CreateContactAdditionalAddressRequest::class => MockResponse::fixture('ContactAdditionalAddresses/create-contact-additional-address'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new CreateContactAdditionalAddressRequest(
        1,
        new CreateEditContactAdditionalAddressDTO(
            name: 'Test',
            subject: 'Test Subject',
            description: 'This is a test',
            street_name: 'Test Street',
            house_number: '42A',
            address_addition: 'c/o Test',
            postcode: '1234',
            city: 'Test City',
        )
    ));

    $mockClient->assertSent(CreateContactAdditionalAddressRequest::class);
});
