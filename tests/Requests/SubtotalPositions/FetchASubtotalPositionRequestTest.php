<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\SubtotalPositions\SubtotalPositionDTO;
use CodebarAg\Bexio\Requests\SubtotalPositions\FetchASubtotalPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can fetch a subtotal position', function () {
    $mockClient = new MockClient([
        FetchASubtotalPositionRequest::class => MockResponse::fixture('SubtotalPositions/fetch-a-subtotal-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new FetchASubtotalPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 1,
    );

    $response = $connector->send($request);

    $mockClient->assertSent(FetchASubtotalPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(SubtotalPositionDTO::class);
});
