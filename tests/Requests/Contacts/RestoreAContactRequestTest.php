<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Contacts\FetchAContactRequest;
use CodebarAg\Bexio\Requests\Contacts\RestoreAContactRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        RestoreAContactRequest::class => MockResponse::fixture('restore-a-contact'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new RestoreAContactRequest(id: 4));

    $mockClient->assertSent(RestoreAContactRequest::class);
});
