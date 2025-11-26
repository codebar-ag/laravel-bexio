<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Items\DeleteAnItemRequest;
use CodebarAg\Bexio\Requests\Items\FetchAListOfItemsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Items/delete-an-item.json';
    $listFixturePath = __DIR__.'/../../Fixtures/Saloon/Items/fetch-a-list-of-items.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($listFixturePath);
    }

    Saloon::fake([
        DeleteAnItemRequest::class => MockResponse::fixture('Items/delete-an-item'),
        FetchAListOfItemsRequest::class => MockResponse::fixture('Items/fetch-a-list-of-items'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $itemsResponse = $connector->send(new FetchAListOfItemsRequest);
    $existingItem = $itemsResponse->dto()->first();

    if (! $existingItem) {
        $this->markTestSkipped('No items found in the system to delete');
    }

    $response = $connector->send(new DeleteAnItemRequest(article_id: $existingItem->id));

    expect($response->successful())->toBeTrue();

    Saloon::assertSent(DeleteAnItemRequest::class);
})->group('items');
