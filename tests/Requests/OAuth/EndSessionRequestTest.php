<?php

use CodebarAg\Bexio\Requests\OAuth\EndSessionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        EndSessionRequest::class => MockResponse::fixture('OAuth/end-session'),
    ]);

    $request = new EndSessionRequest;
    $request->withMockClient($mockClient);

    $response = $request->send();

    $mockClient->assertSent(EndSessionRequest::class);

    expect($response->successful())->toBeTrue();
});
