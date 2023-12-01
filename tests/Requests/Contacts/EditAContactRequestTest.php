<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\CreateEditContactDTO;
use CodebarAg\Bexio\Requests\Contacts\CreateContactRequest;
use CodebarAg\Bexio\Requests\Contacts\EditAContactRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        EditAContactRequest::class => MockResponse::fixture('edit-contact'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new EditAContactRequest(
        6,
        new CreateEditContactDTO(
            user_id: 1,
            owner_id: 1,
            contact_type_id: 1,
            name_1: 'JohnEdited'
        )
    ));

    $mockClient->assertSent(EditAContactRequest::class);
});
