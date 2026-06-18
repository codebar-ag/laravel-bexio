<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Files\FileDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Files\FetchAListOfFilesRequest;
use CodebarAg\Bexio\Requests\Files\GetASingleFileRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAListOfFilesRequest::class => MockResponse::fixture('Files/fetch-a-list-of-files'),
        GetASingleFileRequest::class => MockResponse::fixture('Files/get-a-single-file'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $file = $connector->send(new FetchAListOfFilesRequest)->dto()->first();

    if (! $file) {
        $this->markTestSkipped('A file is required to fetch a single file.');
    }

    $response = $connector->send(new GetASingleFileRequest(
        id: $file->id,
    ));

    Saloon::assertSent(GetASingleFileRequest::class);

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(FileDTO::class);
});
