<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Files\CreateAFileRequest;
use Saloon\Data\MultipartValue;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CreateAFileRequest::class => MockResponse::fixture('Files/create-a-file'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CreateAFileRequest(
        data: [
            new MultipartValue(
                name: 'picture',
                value: fopen(__DIR__.'/../../Fixtures/Files/image.png', 'r'),
            ),
        ],
    ));

    Saloon::assertSent(CreateAFileRequest::class);
});
