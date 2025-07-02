<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\AdditionalAddresses\CreateEditAdditionalAddressDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\AdditionalAddresses\EditAnAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        EditAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/edit-an-additional-address'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new EditAnAdditionalAddressRequest(
        contactId: 1,
        id: 13,
        data: new CreateEditAdditionalAddressDTO(
            name: 'Test name edited',
            subject: 'Test subject edited',
            description: 'Test description edited',
            address: 'Test address edited',
            postcode: '12345',
            city: 'Test city edited',
            country_id: 1,
        )
    ));
    $mockClient->assertSent(EditAnAdditionalAddressRequest::class);
});
