<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ContactGroups\FetchAContactGroupRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAContactGroupRequest::class => MockResponse::fixture('ContactGroups/fetch-a-contact-group'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAContactGroupRequest(id: 9));

    $mockClient->assertSent(FetchAContactGroupRequest::class);
});
