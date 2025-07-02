<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Contacts\CreateEditContactDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Contacts\CreateContactRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        CreateContactRequest::class => MockResponse::fixture('Contacts/create-contact'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new CreateContactRequest(
        new CreateEditContactDTO(
            user_id: 1,
            owner_id: 1,
            contact_type_id: 1,
            name_1: 'John'
        )
    ));

    $mockClient->assertSent(CreateContactRequest::class);
});
