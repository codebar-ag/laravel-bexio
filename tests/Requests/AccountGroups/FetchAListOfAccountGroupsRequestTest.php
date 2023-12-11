<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\AccountGroups\FetchAListOfAccountGroupsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfAccountGroupsRequest::class => MockResponse::fixture('AccountGroups/fetch-a-list-of-account-groups'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfAccountGroupsRequest());

    $mockClient->assertSent(FetchAListOfAccountGroupsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(30);
});
