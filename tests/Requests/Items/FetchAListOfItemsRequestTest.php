<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Items\FetchAListOfItemsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAListOfItemsRequest::class => MockResponse::fixture('Items/fetch-a-list-of-items'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAListOfItemsRequest);

    Saloon::assertSent(FetchAListOfItemsRequest::class);
});
