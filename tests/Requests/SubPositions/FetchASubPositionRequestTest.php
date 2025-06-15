<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\SubPositions\SubPositionDTO;
use CodebarAg\Bexio\Requests\SubPositions\FetchASubPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can fetch a sub position', function () {
    $mockClient = new MockClient([
        FetchASubPositionRequest::class => MockResponse::fixture('SubPositions/fetch-a-sub-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new FetchASubPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 1,
    );

    $response = $connector->send($request);

    $mockClient->assertSent(FetchASubPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(SubPositionDTO::class);
});
