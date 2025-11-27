<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ItemPositions\CreateEditItemPositionDTO;
use CodebarAg\Bexio\Dto\ItemPositions\ItemPositionDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ItemPositions\EditAnItemPositionRequest;
use CodebarAg\Bexio\Requests\ItemPositions\FetchAListOfItemPositionsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/ItemPositions/edit-an-item-position.json';
    $listFixturePath = __DIR__.'/../../Fixtures/Saloon/ItemPositions/fetch-a-list-of-item-positions.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($listFixturePath);
    }

    Saloon::fake([
        EditAnItemPositionRequest::class => MockResponse::fixture('ItemPositions/edit-an-item-position'),
        FetchAListOfItemPositionsRequest::class => MockResponse::fixture('ItemPositions/fetch-a-list-of-item-positions'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $itemPositionsResponse = $connector->send(new FetchAListOfItemPositionsRequest(
        kb_document_id: 1,
        kb_document_type: 'kb_offer'
    ));
    $existingItemPosition = $itemPositionsResponse->dto()->first();

    if (! $existingItemPosition) {
        $this->markTestSkipped('No item positions found in the system to edit');
    }

    $itemPosition = CreateEditItemPositionDTO::fromArray([
        'kb_document_type' => $existingItemPosition->kb_document_type,
        'type' => $existingItemPosition->type,
        'amount' => '2',
        'unit_id' => $existingItemPosition->unit_id,
        'account_id' => $existingItemPosition->account_id,
        'tax_id' => $existingItemPosition->tax_id,
        'text' => 'Updated Item Position',
        'unit_price' => '150.00',
        'discount_in_percent' => '0',
    ]);

    $response = $connector->send(new EditAnItemPositionRequest(
        item_position_id: $existingItemPosition->id,
        itemPosition: $itemPosition
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(ItemPositionDTO::class);

    Saloon::assertSent(EditAnItemPositionRequest::class);
})->group('item-positions');
