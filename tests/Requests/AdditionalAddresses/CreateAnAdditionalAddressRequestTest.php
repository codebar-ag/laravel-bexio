<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\AdditionalAddresses\CreateEditAdditionalAddressDTO;
use CodebarAg\Bexio\Requests\AdditionalAddresses\CreateAnAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        CreateAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/create-an-additional-address'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new CreateAnAdditionalAddressRequest(
        id: 1,
        data: new CreateEditAdditionalAddressDTO(
            name: 'Test name',
            subject: 'Test subject',
            description: 'Test description',
            street_name: 'Test Street',
            house_number: '42A',
            address_addition: 'c/o Test',
            postcode: '12345',
            city: 'Test city',
            country_id: 1,
        )
    ));

    $mockClient->assertSent(CreateAnAdditionalAddressRequest::class);
});
