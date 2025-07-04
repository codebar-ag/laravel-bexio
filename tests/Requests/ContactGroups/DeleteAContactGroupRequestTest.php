<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactGroups\DeleteAContactGroupRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        DeleteAContactGroupRequest::class => MockResponse::fixture('ContactGroups/delete-a-contact-group'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new DeleteAContactGroupRequest(id: 10));

    Saloon::assertSent(DeleteAContactGroupRequest::class);
});
