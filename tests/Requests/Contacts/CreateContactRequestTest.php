<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Contacts\CreateEditContactDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Contacts\CreateContactRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CreateContactRequest::class => MockResponse::fixture('Contacts/create-contact'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CreateContactRequest(
        new CreateEditContactDTO(
            user_id: 1,
            owner_id: 1,
            contact_type_id: 1,
            name_1: 'John'
        )
    ));

    Saloon::assertSent(CreateContactRequest::class);
});
