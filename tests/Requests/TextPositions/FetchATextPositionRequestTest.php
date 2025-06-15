<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\TextPositions\TextPositionDTO;
use CodebarAg\Bexio\Requests\TextPositions\FetchATextPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can fetch a text position', function () {
    $mockClient = new MockClient([
        FetchATextPositionRequest::class => MockResponse::fixture('TextPositions/fetch-a-text-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new FetchATextPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 1,
    );

    $response = $connector->send($request);

    $mockClient->assertSent(FetchATextPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(TextPositionDTO::class);
});
