<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Files\EditFileDTO;
use CodebarAg\Bexio\Dto\Salutations\CreateEditSalutationDTO;
use CodebarAg\Bexio\Requests\Files\EditAFileRequest;
use CodebarAg\Bexio\Requests\Salutations\EditASalutationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        EditAFileRequest::class => MockResponse::fixture('Files/edit-a-file'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new EditAFileRequest(
        id: 9,
        data: new EditFileDTO(
            name: 'Test name edited',
            is_archived: false,
            source_type: 'web',
        )
    ));

    $mockClient->assertSent(EditAFileRequest::class);
});
