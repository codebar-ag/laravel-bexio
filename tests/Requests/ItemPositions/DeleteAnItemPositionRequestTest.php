<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ItemPositions\DeleteAnItemPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can delete an item position', function () {
    $mockClient = new MockClient([
        DeleteAnItemPositionRequest::class => MockResponse::fixture('ItemPositions/delete-an-item-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new DeleteAnItemPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 3,
    );

    $response = $connector->send($request);

    $mockClient->assertSent(DeleteAnItemPositionRequest::class);
    expect($response->dto())->not()->toBeNull();
});
