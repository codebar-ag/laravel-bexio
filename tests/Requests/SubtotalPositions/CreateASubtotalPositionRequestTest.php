<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\SubtotalPositions\CreateEditSubtotalPositionDTO;
use CodebarAg\Bexio\Dto\SubtotalPositions\SubtotalPositionDTO;
use CodebarAg\Bexio\Requests\SubtotalPositions\CreateASubtotalPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', function () {
    $mockClient = new MockClient([
        CreateASubtotalPositionRequest::class => MockResponse::fixture('SubtotalPositions/create-a-subtotal-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new CreateASubtotalPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        position: new CreateEditSubtotalPositionDTO(
            text: 'Test subtotal position',
        )
    );

    $response = $connector->send($request);

    $mockClient->assertSent(CreateASubtotalPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(SubtotalPositionDTO::class);
});
