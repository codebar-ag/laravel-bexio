<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactRelations\DeleteAContactRelationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        DeleteAContactRelationRequest::class => MockResponse::fixture('ContactRelations/delete-a-contact-relation'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new DeleteAContactRelationRequest(id: 3));

    Saloon::assertSent(DeleteAContactRelationRequest::class);
});
