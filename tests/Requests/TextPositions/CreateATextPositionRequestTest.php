<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\TextPositions\CreateEditTextPositionDTO;
use CodebarAg\Bexio\Dto\TextPositions\TextPositionDTO;
use CodebarAg\Bexio\Requests\TextPositions\CreateATextPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', function () {
    $mockClient = new MockClient([
        CreateATextPositionRequest::class => MockResponse::fixture('TextPositions/create-a-text-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new CreateATextPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        position: new CreateEditTextPositionDTO(
            text: 'Test text position',
            show_pos_nr: true,
        )
    );

    $response = $connector->send($request);

    $mockClient->assertSent(CreateATextPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(TextPositionDTO::class);
});
