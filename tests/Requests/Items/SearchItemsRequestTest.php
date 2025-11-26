<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Items\SearchItemsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Items/search-items.json';

    if (shouldResetFixtures()) {
        unlink($fixturePath);
    }

    Saloon::fake([
        SearchItemsRequest::class => MockResponse::fixture('Items/search-items'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new SearchItemsRequest(
        searchField: 'intern_name',
        searchTerm: 'DocuWare'
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(Collection::class);

    Saloon::assertSent(SearchItemsRequest::class);
})->group('items');
