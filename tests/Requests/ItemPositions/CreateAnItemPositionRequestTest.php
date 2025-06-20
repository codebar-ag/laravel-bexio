<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ItemPositions\CreateItemPositionDTO;
use CodebarAg\Bexio\Dto\ItemPositions\ItemPositionDTO;
use CodebarAg\Bexio\Requests\ItemPositions\CreateAnItemPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', function () {
    $mockClient = new MockClient([
        CreateAnItemPositionRequest::class => MockResponse::fixture('ItemPositions/create-an-item-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new CreateAnItemPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        position: new CreateItemPositionDTO(
            amount: 1,
            unit_id: 1,
            account_id: 145,
            tax_id: 29,
            text: 'Test item position',
            unit_price: 110,
            discount_in_percent: '0',
            article_id: 1,
        )
    );

    $response = $connector->send($request);

    $mockClient->assertSent(CreateAnItemPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(ItemPositionDTO::class);
});
