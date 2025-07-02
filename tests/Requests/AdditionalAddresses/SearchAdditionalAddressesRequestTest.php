<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\AdditionalAddresses\SearchAdditionalAddressesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        SearchAdditionalAddressesRequest::class => MockResponse::fixture('AdditionalAddresses/search-additional-addresses'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SearchAdditionalAddressesRequest(
        id: 1,
        searchField: 'name',
        searchTerm: 'Test',
    ));

    $mockClient->assertSent(SearchAdditionalAddressesRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(0);
});
