<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactRelations\CreateEditContactRelationDTO;
use CodebarAg\Bexio\Requests\ContactRelations\CreateContactRelationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        CreateContactRelationRequest::class => MockResponse::fixture('ContactRelations/create-contact-relation'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new CreateContactRelationRequest(
        new CreateEditContactRelationDTO(
            contact_id: 1,
            contact_sub_id: 2,
            description: 'This is a test',
        )
    ));

    $mockClient->assertSent(CreateContactRelationRequest::class);
});
