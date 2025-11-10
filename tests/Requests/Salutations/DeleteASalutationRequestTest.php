<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Salutations\DeleteASalutationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        DeleteASalutationRequest::class => MockResponse::fixture('Salutations/delete-a-salutation'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new DeleteASalutationRequest(
        id: 5
    ));

    Saloon::assertSent(DeleteASalutationRequest::class);
});
