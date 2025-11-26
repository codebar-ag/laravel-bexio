<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Items\CreateEditItemDTO;
use CodebarAg\Bexio\Dto\Items\ItemDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Items\EditAnItemRequest;
use CodebarAg\Bexio\Requests\Items\FetchAListOfItemsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Items/edit-an-item.json';
    $listFixturePath = __DIR__.'/../../Fixtures/Saloon/Items/fetch-a-list-of-items.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($listFixturePath);
    }

    Saloon::fake([
        EditAnItemRequest::class => MockResponse::fixture('Items/edit-an-item'),
        FetchAListOfItemsRequest::class => MockResponse::fixture('Items/fetch-a-list-of-items'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $itemsResponse = $connector->send(new FetchAListOfItemsRequest);
    $existingItem = $itemsResponse->dto()->first();

    if (! $existingItem) {
        $this->markTestSkipped('No items found in the system to edit');
    }

    $response = $connector->send(new EditAnItemRequest(
        article_id: $existingItem->id,
        data: new CreateEditItemDTO(
            user_id: $existingItem->user_id,
            article_type_id: $existingItem->article_type_id,
            contact_id: $existingItem->contact_id,
            deliverer_code: $existingItem->deliverer_code,
            deliverer_name: $existingItem->deliverer_name,
            deliverer_description: $existingItem->deliverer_description,
            intern_code: $existingItem->intern_code,
            intern_name: 'Updated Item Name'
        )
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(ItemDTO::class);

    Saloon::assertSent(EditAnItemRequest::class);
})->group('items');
