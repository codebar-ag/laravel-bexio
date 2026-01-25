<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\AdditionalAddresses\CreateEditAdditionalAddressDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\AdditionalAddresses\EditAnAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        EditAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/edit-an-additional-address'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new EditAnAdditionalAddressRequest(
        contactId: 1,
        id: 13,
        data: new CreateEditAdditionalAddressDTO(
            name: 'Test name edited',
            name_addition: null,
            subject: 'Test subject edited',
            description: 'Test description edited',
            postcode: 12345,
            city: 'Test city edited',
            country_id: 1,
        )
    ));
    Saloon::assertSent(EditAnAdditionalAddressRequest::class);
});
