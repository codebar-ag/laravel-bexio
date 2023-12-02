<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactRelations\CreateEditContactRelationDTO;
use CodebarAg\Bexio\Requests\ContactRelations\EditAContactRelationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        EditAContactRelationRequest::class => MockResponse::fixture('ContactRelations/edit-contact-relation'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new EditAContactRelationRequest(
        2,
        new CreateEditContactRelationDTO(
            contact_id: 2,
            contact_sub_id: 1,
            description: 'This is a test edit',
        )
    ));

    $mockClient->assertSent(EditAContactRelationRequest::class);
});
