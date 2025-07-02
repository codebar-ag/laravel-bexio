<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Salutations\CreateEditSalutationDTO;
use CodebarAg\Bexio\Requests\Salutations\CreateASalutationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CreateASalutationRequest::class => MockResponse::fixture('Salutations/create-a-salutation'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CreateASalutationRequest(
        data: new CreateEditSalutationDTO(
            name: 'Test name',
        )
    ));

    Saloon::assertSent(CreateASalutationRequest::class);
});
