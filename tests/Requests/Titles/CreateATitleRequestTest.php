<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Titles\CreateEditTitleDTO;
use CodebarAg\Bexio\Requests\Titles\CreateATitleRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CreateATitleRequest::class => MockResponse::fixture('Titles/create-a-title'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CreateATitleRequest(
        data: new CreateEditTitleDTO(
            name: 'Test name',
        )
    ));

    Saloon::assertSent(CreateATitleRequest::class);
});
