<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Items\ItemDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Items\FetchAListOfItemsRequest;
use CodebarAg\Bexio\Requests\Items\FetchAnItemRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Items/fetch-an-item.json';
    $listFixturePath = __DIR__.'/../../Fixtures/Saloon/Items/fetch-a-list-of-items.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($listFixturePath);
    }

    Saloon::fake([
        FetchAnItemRequest::class => MockResponse::fixture('Items/fetch-an-item'),
        FetchAListOfItemsRequest::class => MockResponse::fixture('Items/fetch-a-list-of-items'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $itemsResponse = $connector->send(new FetchAListOfItemsRequest);
    $existingItem = $itemsResponse->dto()->first();

    if (! $existingItem) {
        $this->markTestSkipped('No items found in the system to fetch');
    }

    $response = $connector->send(new FetchAnItemRequest(article_id: $existingItem->id));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(ItemDTO::class);

    Saloon::assertSent(FetchAnItemRequest::class);
})->group('items');
