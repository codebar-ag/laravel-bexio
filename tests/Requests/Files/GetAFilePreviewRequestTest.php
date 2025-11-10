<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Files\GetAFilePreviewRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        GetAFilePreviewRequest::class => MockResponse::fixture('Files/get-a-file-preview'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new GetAFilePreviewRequest(
        id: 8,
    ));

    file_put_contents(__DIR__.'/../../Fixtures/Files/image-preview.png', $response->stream());

    Saloon::assertSent(GetAFilePreviewRequest::class);
});
