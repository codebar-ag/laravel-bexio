<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Titles\CreateEditTitleDTO;
use CodebarAg\Bexio\Requests\Titles\EditATitleRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        EditATitleRequest::class => MockResponse::fixture('Titles/edit-a-title'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new EditATitleRequest(
        id: 4,
        data: new CreateEditTitleDTO(
            name: 'Test name edited',
        )
    ));

    Saloon::assertSent(EditATitleRequest::class);
});
