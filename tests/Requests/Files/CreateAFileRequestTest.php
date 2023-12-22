<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Salutations\CreateEditSalutationDTO;
use CodebarAg\Bexio\Requests\Files\CreateAFileRequest;
use CodebarAg\Bexio\Requests\Salutations\CreateASalutationRequest;
use Saloon\Data\MultipartValue;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        CreateAFileRequest::class => MockResponse::fixture('Files/create-a-file'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new CreateAFileRequest(
        data: [
            new MultipartValue(
                name: 'picture',
                value: fopen(__DIR__ . '/../../Fixtures/Files/image.png', 'r'),
            )
        ],
    ));

    $mockClient->assertSent(CreateAFileRequest::class);
});
