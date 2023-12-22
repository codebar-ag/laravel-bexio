<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Files\DownloadFileRequest;
use CodebarAg\Bexio\Requests\Files\GetAFilePreviewRequest;
use CodebarAg\Bexio\Requests\Files\GetASingleFileRequest;
use CodebarAg\Bexio\Requests\Salutations\FetchASalutationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        DownloadFileRequest::class => MockResponse::fixture('Files/download-file'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new DownloadFileRequest(
        id: 8,
    ));

    file_put_contents(__DIR__ . '/../../Fixtures/Files/image-download.png', $response->stream());

    $mockClient->assertSent(DownloadFileRequest::class);
});
