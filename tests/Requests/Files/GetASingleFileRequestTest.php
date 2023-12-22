<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Files\GetASingleFileRequest;
use CodebarAg\Bexio\Requests\Salutations\FetchASalutationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        GetASingleFileRequest::class => MockResponse::fixture('Files/get-a-single-file'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new GetASingleFileRequest(
        id: 4,
    ));

    $mockClient->assertSent(GetASingleFileRequest::class);
});
