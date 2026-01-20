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
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/ItemPositions/edit-an-item-position';

    if (shouldResetFixtures()) {
        @unlink($fixturePath.'/fetch-a-list-of-item-positions.json');
        @unlink($fixturePath.'/edit-an-item-position.json');
    }

    $mockItemPositions = [
        [
            'id' => 1,
            'kb_document_type' => 'kb_offer',
            'kb_position_id' => 1,
            'type' => 'KbPositionCustom',
            'amount' => '1',
            'unit_id' => 1,
            'unit_name' => 'Stk',
            'account_id' => 1,
            'tax_id' => 1,
            'tax_value' => '8.10',
            'text' => 'Test Item Position',
            'unit_price' => '100.00',
            'discount_in_percent' => '0',
            'position_total' => '100.00',
            'parent_id' => null,
            'article_id' => null,
            'show_pos_nr' => true,
            'pagebreak' => false,
            'is_percentual' => false,
            'value' => null,
            'pos' => '1',
            'internal_pos' => 1,
            'is_optional' => false,
        ],
    ];

    $mockUpdatedItemPosition = [
        'id' => 1,
        'kb_document_type' => 'kb_offer',
        'kb_position_id' => 1,
        'type' => 'KbPositionCustom',
        'amount' => '2',
        'unit_id' => 1,
        'unit_name' => 'Stk',
        'account_id' => 1,
        'tax_id' => 1,
        'tax_value' => '8.10',
        'text' => 'Updated Item Position',
        'unit_price' => '150.00',
        'discount_in_percent' => '0',
        'position_total' => '300.00',
        'parent_id' => null,
        'article_id' => null,
        'show_pos_nr' => true,
        'pagebreak' => false,
        'is_percentual' => false,
        'value' => null,
        'pos' => '1',
        'internal_pos' => 1,
        'is_optional' => false,
    ];

    Saloon::fake([
        FetchAListOfItemPositionsRequest::class => MockResponse::make(body: $mockItemPositions, status: 200),
        EditAnItemPositionRequest::class => MockResponse::make(body: $mockUpdatedItemPosition, status: 200),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $kbDocumentType = 'kb_offer';
    $itemPositionsResponse = $connector->send(new FetchAListOfItemPositionsRequest(
        kb_document_id: 1,
        kb_document_type: $kbDocumentType
    ));
    $existingItemPosition = $itemPositionsResponse->dto()->first();

    if (! $existingItemPosition) {
        $this->markTestSkipped('No item positions found in the system to edit');
    }

    $itemPosition = CreateEditItemPositionDTO::fromArray([
        'kb_document_type' => $kbDocumentType,
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
        kb_document_id: 1,
        item_position_id: $existingItemPosition->id,
        itemPosition: $itemPosition
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(ItemPositionDTO::class);

    Saloon::assertSent(EditAnItemPositionRequest::class);
})->group('item-positions');
