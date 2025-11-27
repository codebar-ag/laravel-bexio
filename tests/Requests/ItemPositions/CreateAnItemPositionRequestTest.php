<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ItemPositions\CreateEditItemPositionDTO;
use CodebarAg\Bexio\Dto\ItemPositions\ItemPositionDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ItemPositions\CreateAnItemPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/ItemPositions/create-an-item-position.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    $mockItemPosition = [
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
    ];

    Saloon::fake([
        CreateAnItemPositionRequest::class => MockResponse::make(body: $mockItemPosition, status: 201),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $itemPosition = CreateEditItemPositionDTO::fromArray([
        'kb_document_type' => 'kb_offer',
        'type' => 'KbPositionCustom',
        'amount' => '1',
        'unit_id' => 1,
        'account_id' => 1,
        'tax_id' => 1,
        'text' => 'Test Item Position',
        'unit_price' => '100.00',
        'discount_in_percent' => '0',
    ]);

    $response = $connector->send(new CreateAnItemPositionRequest(
        kb_document_id: 1,
        itemPosition: $itemPosition
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(ItemPositionDTO::class);

    Saloon::assertSent(CreateAnItemPositionRequest::class);
})->group('item-positions');
