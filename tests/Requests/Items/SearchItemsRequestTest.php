<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Items\SearchItemsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        SearchItemsRequest::class => MockResponse::fixture('Items/search-items'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new SearchItemsRequest(
        searchField: 'intern_name',
        searchTerm: 'Test'
    ));

    Saloon::assertSent(SearchItemsRequest::class);
});
