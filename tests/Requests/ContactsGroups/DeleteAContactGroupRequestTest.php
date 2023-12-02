<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ContactGroups\DeleteAContactGroupRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        DeleteAContactGroupRequest::class => MockResponse::fixture('ContactGroups/delete-a-contact-group'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new DeleteAContactGroupRequest(id: 10));

    $mockClient->assertSent(DeleteAContactGroupRequest::class);
});
