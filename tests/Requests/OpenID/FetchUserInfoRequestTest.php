<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\OpenID\UserInfoDTO;
use CodebarAg\Bexio\Requests\OAuth\OpenIDConfigurationRequest;
use CodebarAg\Bexio\Requests\OpenID\FetchUserInfoRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchUserInfoRequest::class => MockResponse::fixture('OpenID/fetch-user-info'),
        OpenIDConfigurationRequest::class => MockResponse::fixture('OAuth/openid-configuration'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchUserInfoRequest);

    Saloon::assertSent(FetchUserInfoRequest::class);

    expect($response->dto())->toBeInstanceOf(UserInfoDTO::class);
});
