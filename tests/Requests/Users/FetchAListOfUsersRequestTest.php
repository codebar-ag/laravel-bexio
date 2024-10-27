<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Users\FetchAListOfUsersRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfUsersRequest::class => MockResponse::fixture('Users/fetch-a-list-of-users'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfUsersRequest);

    $mockClient->assertSent(FetchAListOfUsersRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(1);
});
