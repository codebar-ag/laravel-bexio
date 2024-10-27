<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Users\UserDTO;
use CodebarAg\Bexio\Requests\Users\FetchAuthenticatedUserRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAuthenticatedUserRequest::class => MockResponse::fixture('Users/fetch-authenticated-user'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAuthenticatedUserRequest);

    $mockClient->assertSent(FetchAuthenticatedUserRequest::class);

    expect($response->dto())->toBeInstanceOf(UserDTO::class);
});
