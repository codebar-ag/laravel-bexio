<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ItemPositions\DeleteAnItemPositionRequest;
use CodebarAg\Bexio\Requests\ItemPositions\FetchAListOfItemPositionsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/ItemPositions/delete-an-item-position.json';
    $listFixturePath = __DIR__.'/../../Fixtures/Saloon/ItemPositions/fetch-a-list-of-item-positions.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($listFixturePath);
    }

    Saloon::fake([
        DeleteAnItemPositionRequest::class => MockResponse::fixture('ItemPositions/delete-an-item-position'),
        FetchAListOfItemPositionsRequest::class => MockResponse::fixture('ItemPositions/fetch-a-list-of-item-positions'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $itemPositionsResponse = $connector->send(new FetchAListOfItemPositionsRequest(
        kb_document_id: 1,
        kb_document_type: 'kb_offer'
    ));
    $existingItemPosition = $itemPositionsResponse->dto()->first();

    if (! $existingItemPosition) {
        $this->markTestSkipped('No item positions found in the system to delete');
    }

    $response = $connector->send(new DeleteAnItemPositionRequest(item_position_id: $existingItemPosition->id));

    expect($response->successful())->toBeTrue();

    Saloon::assertSent(DeleteAnItemPositionRequest::class);
})->group('item-positions');
