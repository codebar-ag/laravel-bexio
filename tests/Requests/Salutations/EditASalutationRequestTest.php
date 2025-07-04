<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Salutations\CreateEditSalutationDTO;
use CodebarAg\Bexio\Requests\Salutations\EditASalutationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        EditASalutationRequest::class => MockResponse::fixture('Salutations/edit-a-salutation'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new EditASalutationRequest(
        id: 5,
        data: new CreateEditSalutationDTO(
            name: 'Test name edited',
        )
    ));

    Saloon::assertSent(EditASalutationRequest::class);
});
