<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ItemPositions\ItemPositionDTO;
use CodebarAg\Bexio\Requests\ItemPositions\FetchAnItemPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can fetch an item position', function () {
    $mockClient = new MockClient([
        FetchAnItemPositionRequest::class => MockResponse::fixture('ItemPositions/fetch-an-item-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new FetchAnItemPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 1,
    );

    $response = $connector->send($request);

    $mockClient->assertSent(FetchAnItemPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(ItemPositionDTO::class);
});
