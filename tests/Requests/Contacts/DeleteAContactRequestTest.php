<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Contacts\DeleteAContactRequest;
use CodebarAg\Bexio\Requests\Contacts\FetchAContactRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        DeleteAContactRequest::class => MockResponse::fixture('delete-a-contact'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new DeleteAContactRequest(id: 4));

    $mockClient->assertSent(DeleteAContactRequest::class);
});
