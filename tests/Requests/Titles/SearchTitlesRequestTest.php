<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Titles\SearchTitlesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        SearchTitlesRequest::class => MockResponse::fixture('Titles/search-titles'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SearchTitlesRequest(
        searchField: 'name',
        searchTerm: 'Dr.',
    ));

    $mockClient->assertSent(SearchTitlesRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(1);
});
