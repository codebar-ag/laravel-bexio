<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactGroups\DeleteAContactGroupRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        DeleteAContactGroupRequest::class => MockResponse::fixture('ContactGroups/delete-a-contact-group'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new DeleteAContactGroupRequest(id: 10));

    $mockClient->assertSent(DeleteAContactGroupRequest::class);
});
