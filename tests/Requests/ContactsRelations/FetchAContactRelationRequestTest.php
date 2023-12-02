<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ContactRelations\FetchAContactRelationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        FetchAContactRelationRequest::class => MockResponse::fixture('ContactRelations/fetch-a-contact-relation'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAContactRelationRequest(id: 2));

    $mockClient->assertSent(FetchAContactRelationRequest::class);
});
