<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Files\GetASingleFileRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        GetASingleFileRequest::class => MockResponse::fixture('Files/get-a-single-file'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new GetASingleFileRequest(
        id: 4,
    ));

    Saloon::assertSent(GetASingleFileRequest::class);
});
