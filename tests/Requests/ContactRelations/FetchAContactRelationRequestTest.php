<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactRelations\FetchAContactRelationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAContactRelationRequest::class => MockResponse::fixture('ContactRelations/fetch-a-contact-relation'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAContactRelationRequest(id: 2));

    Saloon::assertSent(FetchAContactRelationRequest::class);
});
