<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Salutations\SearchSalutationsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        SearchSalutationsRequest::class => MockResponse::fixture('Salutations/search-salutations'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SearchSalutationsRequest(
        searchField: 'name',
        searchTerm: 'Herr',
    ));

    $mockClient->assertSent(SearchSalutationsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(1);
});
