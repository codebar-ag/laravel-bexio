<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\TextPositions\CreateEditTextPositionDTO;
use CodebarAg\Bexio\Dto\TextPositions\TextPositionDTO;
use CodebarAg\Bexio\Requests\TextPositions\EditATextPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can edit a text position', function () {
    $mockClient = new MockClient([
        EditATextPositionRequest::class => MockResponse::fixture('TextPositions/edit-a-text-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new EditATextPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 1,
        position: new CreateEditTextPositionDTO(
            text: 'Test edited text position',
            show_pos_nr: true,
        ),
    );

    $response = $connector->send($request);

    $mockClient->assertSent(EditATextPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(TextPositionDTO::class);
});
