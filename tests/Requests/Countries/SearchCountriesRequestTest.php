<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Countries\SearchCountriesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Countries/search-countries.json';

    if (shouldResetFixtures() && file_exists($fixturePath)) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        SearchCountriesRequest::class => MockResponse::fixture('Countries/search-countries'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new SearchCountriesRequest(
        searchField: 'name_short',
        searchTerm: 'TC'
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(Collection::class);

    Saloon::assertSent(SearchCountriesRequest::class);
})->group('countries');
