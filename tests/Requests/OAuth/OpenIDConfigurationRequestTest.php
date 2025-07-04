<?php

use CodebarAg\Bexio\Dto\OAuthConfiguration\OpenIDConfigurationDTO;
use CodebarAg\Bexio\Requests\OAuth\OpenIDConfigurationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        OpenIDConfigurationRequest::class => MockResponse::fixture('OAuth/openid-configuration'),
    ]);

    $request = new OpenIDConfigurationRequest;
    $response = $request->send();

    Saloon::assertSent(OpenIDConfigurationRequest::class);

    expect($response->dto())->toBeInstanceOf(OpenIDConfigurationDTO::class);
});
