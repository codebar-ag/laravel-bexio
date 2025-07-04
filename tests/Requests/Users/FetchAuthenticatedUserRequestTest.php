<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Users\UserDTO;
use CodebarAg\Bexio\Requests\Users\FetchAuthenticatedUserRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAuthenticatedUserRequest::class => MockResponse::fixture('Users/fetch-authenticated-user'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAuthenticatedUserRequest);

    Saloon::assertSent(FetchAuthenticatedUserRequest::class);

    expect($response->dto())->toBeInstanceOf(UserDTO::class);
});
