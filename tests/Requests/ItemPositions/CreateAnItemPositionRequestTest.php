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

    Saloon::fake([
        CreateAnItemPositionRequest::class => MockResponse::fixture('ItemPositions/create-an-item-position'),
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
