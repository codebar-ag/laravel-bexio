<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Files\DownloadFileRequest;
use CodebarAg\Bexio\Requests\Files\FetchAListOfFilesRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAListOfFilesRequest::class => MockResponse::fixture('Files/fetch-a-list-of-files'),
        DownloadFileRequest::class => MockResponse::fixture('Files/download-file'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $file = $connector->send(new FetchAListOfFilesRequest)->dto()->first();

    if (! $file) {
        $this->markTestSkipped('A file is required to download a file.');
    }

    $response = $connector->send(new DownloadFileRequest(
        id: $file->id,
    ));

    file_put_contents(__DIR__.'/../../Fixtures/Files/image-download.png', $response->stream());

    Saloon::assertSent(DownloadFileRequest::class);

    expect($response->successful())->toBeTrue();
});
