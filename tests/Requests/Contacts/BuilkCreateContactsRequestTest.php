<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\CreateEditContactDTO;
use CodebarAg\Bexio\Requests\Contacts\BulkCreateContactsRequest;
use CodebarAg\Bexio\Requests\Contacts\CreateContactRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
//        BulkCreateContactsRequest::class => MockResponse::fixture('bulk-create-contacts'),
    ]);

    $connector = new BexioConnector;
//    $connector->withMockClient($mockClient);

    $req = new BulkCreateContactsRequest([
        new CreateEditContactDTO(
            user_id: 1,
            owner_id: 1,
            contact_type_id: 1,
            name_1: 'Paul'
        ),
        new CreateEditContactDTO(
            user_id: 1,
            owner_id: 1,
            contact_type_id: 1,
            name_1: 'Terry'
        )
    ]);

    $response = $connector->send($req);

    $mockClient->assertSent(BulkCreateContactsRequest::class);
})->skip();
