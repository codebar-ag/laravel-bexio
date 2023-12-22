<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Salutations\DeleteASalutationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        DeleteASalutationRequest::class => MockResponse::fixture('Salutations/delete-a-salutation'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new DeleteASalutationRequest(
        id: 5
    ));

    $mockClient->assertSent(DeleteASalutationRequest::class);
});
