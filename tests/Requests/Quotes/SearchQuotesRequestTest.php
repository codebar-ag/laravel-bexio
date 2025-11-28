<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Quotes\SearchQuotesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Quotes/search-quotes';

    if (shouldResetFixtures()) {
        @unlink($fixturePath.'/search-quotes.json');
    }

    Saloon::fake([
        SearchQuotesRequest::class => MockResponse::fixture('Quotes/search-quotes/search-quotes'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new SearchQuotesRequest(
        searchField: 'title',
        searchTerm: 'Test'
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(Collection::class);

    Saloon::assertSent(SearchQuotesRequest::class);
})->group('quotes');
