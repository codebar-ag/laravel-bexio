<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Contacts\CreateEditContactDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Contacts\BulkCreateContactsRequest;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        //                BulkCreateContactsRequest::class => MockResponse::fixture('Contacts/bulk-create-contacts'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
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
        ),
    ]);

    $response = $connector->send($req);

    dd($response->json());

    $mockClient->assertSent(BulkCreateContactsRequest::class);
})->skip();
