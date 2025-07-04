<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Titles\DeleteATitleRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        DeleteATitleRequest::class => MockResponse::fixture('Titles/delete-a-title'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new DeleteATitleRequest(
        id: 4
    ));

    Saloon::assertSent(DeleteATitleRequest::class);
});
