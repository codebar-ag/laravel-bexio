<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Titles\SearchTitlesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        SearchTitlesRequest::class => MockResponse::fixture('Titles/search-titles'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new SearchTitlesRequest(
        searchField: 'name',
        searchTerm: 'Dr.',
    ));

    Saloon::assertSent(SearchTitlesRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(1);
});
