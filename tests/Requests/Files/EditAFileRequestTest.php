<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Files\EditFileDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Files\EditAFileRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        EditAFileRequest::class => MockResponse::fixture('Files/edit-a-file'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new EditAFileRequest(
        id: 9,
        data: new EditFileDTO(
            name: 'Test name edited',
            is_archived: false,
            source_type: 'web',
        )
    ));

    Saloon::assertSent(EditAFileRequest::class);
});
