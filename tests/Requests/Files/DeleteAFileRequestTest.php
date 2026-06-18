<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Files\CreateAFileRequest;
use CodebarAg\Bexio\Requests\Files\DeleteAFileRequest;
use Saloon\Data\MultipartValue;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CreateAFileRequest::class => MockResponse::fixture('Files/create-a-file-for-delete'),
        DeleteAFileRequest::class => MockResponse::fixture('Files/delete-a-file'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $file = $connector->send(new CreateAFileRequest(
        data: [
            new MultipartValue(
                name: 'picture',
                value: fopen(__DIR__.'/../../Fixtures/Files/image.png', 'r'),
            ),
        ],
    ))->dto()->first();

    if (! $file) {
        $this->markTestSkipped('A file is required to delete a file.');
    }

    $response = $connector->send(new DeleteAFileRequest(
        id: $file->id
    ));

    Saloon::assertSent(DeleteAFileRequest::class);

    expect($response->successful())->toBeTrue();
});
