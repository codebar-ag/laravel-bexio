<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Files\FetchAListOfFilesRequest;
use CodebarAg\Bexio\Requests\Files\GetAFilePreviewRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAListOfFilesRequest::class => MockResponse::fixture('Files/fetch-a-list-of-files'),
        GetAFilePreviewRequest::class => MockResponse::fixture('Files/get-a-file-preview'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $file = $connector->send(new FetchAListOfFilesRequest)->dto()->first();

    if (! $file) {
        $this->markTestSkipped('A file is required to get a file preview.');
    }

    $response = $connector->send(new GetAFilePreviewRequest(
        id: $file->id,
    ));

    file_put_contents(__DIR__.'/../../Fixtures/Files/image-preview.png', $response->stream());

    Saloon::assertSent(GetAFilePreviewRequest::class);

    expect($response->successful())->toBeTrue();
});
