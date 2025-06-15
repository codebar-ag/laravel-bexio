<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\SubtotalPositions\CreateEditSubtotalPositionDTO;
use CodebarAg\Bexio\Dto\SubtotalPositions\SubtotalPositionDTO;
use CodebarAg\Bexio\Requests\SubtotalPositions\EditASubtotalPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can edit a subtotal position', function () {
    $mockClient = new MockClient([
        EditASubtotalPositionRequest::class => MockResponse::fixture('SubtotalPositions/edit-a-subtotal-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new EditASubtotalPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 1,
        position: new CreateEditSubtotalPositionDTO(
            text: 'Test edited subtotal position',
        ),
    );

    $response = $connector->send($request);

    $mockClient->assertSent(EditASubtotalPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(SubtotalPositionDTO::class);
});
