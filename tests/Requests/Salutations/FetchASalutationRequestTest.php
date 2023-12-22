<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Salutations\FetchASalutationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchASalutationRequest::class => MockResponse::fixture('AdditionalAddresses/fetch-a-salutation'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchASalutationRequest(
        id: 1,
    ));

    $mockClient->assertSent(FetchASalutationRequest::class);
});
