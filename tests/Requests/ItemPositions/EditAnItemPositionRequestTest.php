<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ItemPositions\EditItemPositionDTO;
use CodebarAg\Bexio\Dto\ItemPositions\ItemPositionDTO;
use CodebarAg\Bexio\Requests\ItemPositions\EditAnItemPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can edit an item position', function () {
    $mockClient = new MockClient([
        EditAnItemPositionRequest::class => MockResponse::fixture('ItemPositions/edit-an-item-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new EditAnItemPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 1,
        position: new EditItemPositionDTO(
            amount: 1,
            unit_id: 1,
            account_id: 145,
            tax_id: 29,
            text: 'Test edited item position',
            unit_price: 100,
            discount_in_percent: '0',
        ),
    );

    $response = $connector->send($request);

    $mockClient->assertSent(EditAnItemPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(ItemPositionDTO::class);
});
