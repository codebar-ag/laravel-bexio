<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ContactRelations\DeleteAContactRelationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        DeleteAContactRelationRequest::class => MockResponse::fixture('ContactRelations/delete-a-contact-relation'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new DeleteAContactRelationRequest(id: 3));

    $mockClient->assertSent(DeleteAContactRelationRequest::class);
});
