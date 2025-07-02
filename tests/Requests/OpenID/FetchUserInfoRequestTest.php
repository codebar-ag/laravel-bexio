<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\OpenID\UserInfoDTO;
use CodebarAg\Bexio\Requests\OAuth\OpenIDConfigurationRequest;
use CodebarAg\Bexio\Requests\OpenID\FetchUserInfoRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchUserInfoRequest::class => MockResponse::fixture('OpenID/fetch-user-info'),
        OpenIDConfigurationRequest::class => MockResponse::fixture('OAuth/openid-configuration'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchUserInfoRequest);

    $mockClient->assertSent(FetchUserInfoRequest::class);

    expect($response->dto())->toBeInstanceOf(UserInfoDTO::class);
});
