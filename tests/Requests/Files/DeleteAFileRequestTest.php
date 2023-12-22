<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Files\DeleteAFileRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        DeleteAFileRequest::class => MockResponse::fixture('Files/delete-a-file'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new DeleteAFileRequest(
        id: 5
    ));

    $mockClient->assertSent(DeleteAFileRequest::class);
});
