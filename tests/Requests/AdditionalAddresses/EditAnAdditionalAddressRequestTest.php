<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\AdditionalAddresses\CreateEditAdditionalAddressDTO;
use CodebarAg\Bexio\Requests\AdditionalAddresses\EditAnAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        EditAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/edit-an-additional-address'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new EditAnAdditionalAddressRequest(
        contactId: 1,
        id: 37,
        data: new CreateEditAdditionalAddressDTO(
            name: 'Test name edited',
            subject: 'Test subject edited',
            description: 'Test description edited',
            street_name: 'Test Street edited',
            house_number: '42B',
            address_addition: 'c/o Test edited',
            postcode: '12345',
            city: 'Test city edited',
            country_id: 1,
        )
    );

    $response = $connector->send($request);
    $mockClient->assertSent(EditAnAdditionalAddressRequest::class);
});
