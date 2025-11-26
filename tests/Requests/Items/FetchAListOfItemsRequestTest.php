<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Items\FetchAListOfItemsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Items/fetch-a-list-of-items.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        FetchAListOfItemsRequest::class => MockResponse::fixture('Items/fetch-a-list-of-items'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $response = $connector->send(new FetchAListOfItemsRequest);

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(Collection::class);

    Saloon::assertSent(FetchAListOfItemsRequest::class);
})->group('items');
