<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\SubPositions\CreateEditSubPositionDTO;
use CodebarAg\Bexio\Dto\SubPositions\SubPositionDTO;
use CodebarAg\Bexio\Requests\SubPositions\CreateASubPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', function () {
    $mockClient = new MockClient([
        CreateASubPositionRequest::class => MockResponse::fixture('SubPositions/create-a-sub-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new CreateASubPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        position: new CreateEditSubPositionDTO(
            text: 'Test sub position',
            show_pos_nr: true,
        )
    );

    $response = $connector->send($request);

    $mockClient->assertSent(CreateASubPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(SubPositionDTO::class);
});
