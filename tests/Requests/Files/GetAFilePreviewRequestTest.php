<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Files\GetAFilePreviewRequest;
use CodebarAg\Bexio\Requests\Files\GetASingleFileRequest;
use CodebarAg\Bexio\Requests\Salutations\FetchASalutationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        GetAFilePreviewRequest::class => MockResponse::fixture('Files/get-a-file-preview'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new GetAFilePreviewRequest(
        id: 8,
    ));

    file_put_contents(__DIR__ . '/../../Fixtures/Files/image-preview.png', $response->stream());

    $mockClient->assertSent(GetAFilePreviewRequest::class);
});
