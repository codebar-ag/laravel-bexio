<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Files\EditFileDTO;
use CodebarAg\Bexio\Dto\Files\FileDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Files\EditAFileRequest;
use CodebarAg\Bexio\Requests\Files\FetchAListOfFilesRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAListOfFilesRequest::class => MockResponse::fixture('Files/fetch-a-list-of-files'),
        EditAFileRequest::class => MockResponse::fixture('Files/edit-a-file'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $file = $connector->send(new FetchAListOfFilesRequest)->dto()->first();

    if (! $file) {
        $this->markTestSkipped('A file is required to edit a file.');
    }

    $response = $connector->send(new EditAFileRequest(
        id: $file->id,
        data: new EditFileDTO(
            name: 'Test name edited',
            is_archived: false,
            source_type: 'web',
        )
    ));

    Saloon::assertSent(EditAFileRequest::class);

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(FileDTO::class);
});
