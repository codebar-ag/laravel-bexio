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

    Saloon::fake([
        DeleteAnItemPositionRequest::class => MockResponse::make(status: 204),
        FetchAListOfItemPositionsRequest::class => MockResponse::make(body: $mockItemPositions, status: 200),
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
