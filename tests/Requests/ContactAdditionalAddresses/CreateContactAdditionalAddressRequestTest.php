<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactAdditionalAddresses\CreateEditContactAdditionalAddressDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactAdditionalAddresses\CreateContactAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CreateContactAdditionalAddressRequest::class => MockResponse::fixture('ContactAdditionalAddresses/create-contact-additional-address'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CreateContactAdditionalAddressRequest(
        1,
        new CreateEditContactAdditionalAddressDTO(
            name: 'Test',
            subject: 'Test Subject',
            description: 'This is a test',
            address: 'Test Address',
            postcode: '1234',
            city: 'Test City',
        )
    ));

    Saloon::assertSent(CreateContactAdditionalAddressRequest::class);
});
