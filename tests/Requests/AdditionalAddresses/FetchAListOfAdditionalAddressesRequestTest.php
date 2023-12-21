<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\AdditionalAddresses\FetchAListOfAdditionalAddressesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfAdditionalAddressesRequest::class => MockResponse::fixture('AdditionalAddresses/fetch-a-list-of-additional-addresses'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfAdditionalAddressesRequest(
        id: 1,
    ));

    $mockClient->assertSent(FetchAListOfAdditionalAddressesRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(2);
});
