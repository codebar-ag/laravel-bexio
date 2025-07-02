<?php

use CodebarAg\Bexio\Dto\OAuthConfiguration\OpenIDConfigurationDTO;
use CodebarAg\Bexio\Requests\OAuth\OpenIDConfigurationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        OpenIDConfigurationRequest::class => MockResponse::fixture('OAuth/openid-configuration'),
    ]);

    $request = new OpenIDConfigurationRequest;
    $request->withMockClient($mockClient);

    $response = $request->send();

    $mockClient->assertSent(OpenIDConfigurationRequest::class);

    expect($response->dto())->toBeInstanceOf(OpenIDConfigurationDTO::class);
});
