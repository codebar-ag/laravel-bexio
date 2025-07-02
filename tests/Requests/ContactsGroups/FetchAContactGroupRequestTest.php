<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactGroups\FetchAContactGroupRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAContactGroupRequest::class => MockResponse::fixture('ContactGroups/fetch-a-contact-group'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAContactGroupRequest(id: 9));

    Saloon::assertSent(FetchAContactGroupRequest::class);
});
