<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\SubtotalPositions\DeleteASubtotalPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can delete a subtotal position', function () {
    $mockClient = new MockClient([
        DeleteASubtotalPositionRequest::class => MockResponse::fixture('SubtotalPositions/delete-a-subtotal-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new DeleteASubtotalPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 2,
    );

    $response = $connector->send($request);

    $mockClient->assertSent(DeleteASubtotalPositionRequest::class);
    expect($response->dto())->not()->toBeNull();
});
