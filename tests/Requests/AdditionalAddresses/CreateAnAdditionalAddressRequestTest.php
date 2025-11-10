<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\AdditionalAddresses\CreateEditAdditionalAddressDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\AdditionalAddresses\CreateAnAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CreateAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/create-an-additional-address'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CreateAnAdditionalAddressRequest(
        contactId: 1,
        data: new CreateEditAdditionalAddressDTO(
            name: 'Test name',
            name_addition: null,
            subject: 'Test subject',
            description: 'Test description',
            address: 'Test address',
            postcode: '12345',
            city: 'Test city',
            country_id: 1,
        )
    ));

    Saloon::assertSent(CreateAnAdditionalAddressRequest::class);
});
