<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\SubPositions\CreateEditSubPositionDTO;
use CodebarAg\Bexio\Dto\SubPositions\SubPositionDTO;
use CodebarAg\Bexio\Requests\SubPositions\EditASubPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can edit a sub position', function () {
    $mockClient = new MockClient([
        EditASubPositionRequest::class => MockResponse::fixture('SubPositions/edit-a-sub-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new EditASubPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 1,
        position: new CreateEditSubPositionDTO(
            text: 'Test edited sub position',
            show_pos_nr: true,
        ),
    );

    $response = $connector->send($request);

    $mockClient->assertSent(EditASubPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(SubPositionDTO::class);
});
