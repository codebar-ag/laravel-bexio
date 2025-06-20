<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\DefaultPositions\DefaultPositionDTO;
use CodebarAg\Bexio\Requests\DefaultPositions\FetchADefaultPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can fetch a default position', function () {
    $mockClient = new MockClient([
        FetchADefaultPositionRequest::class => MockResponse::fixture('DefaultPositions/fetch-a-default-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new FetchADefaultPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 9,
    );

    $response = $connector->send($request);

    $mockClient->assertSent(FetchADefaultPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(DefaultPositionDTO::class);
});
