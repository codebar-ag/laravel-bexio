<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Accounts\FetchAListOfAccountsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfAccountsRequest::class => MockResponse::fixture('Accounts/fetch-a-list-of-accounts'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfAccountsRequest());

    $mockClient->assertSent(FetchAListOfAccountsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(27);
});
