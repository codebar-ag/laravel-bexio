<?php

use CodebarAg\Bexio\Requests\OAuth\EndSessionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        EndSessionRequest::class => MockResponse::fixture('OAuth/end-session'),
    ]);

    $request = new EndSessionRequest;$response = $request->send();

    Saloon::assertSent(EndSessionRequest::class);

    expect($response->successful())->toBeTrue();
});
