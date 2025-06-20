<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\PagebreakPositions\PagebreakPositionDTO;
use CodebarAg\Bexio\Requests\PagebreakPositions\FetchAPagebreakPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can fetch a pagebreak position', function () {
    $mockClient = new MockClient([
        FetchAPagebreakPositionRequest::class => MockResponse::fixture('PagebreakPositions/fetch-a-pagebreak-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new FetchAPagebreakPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 1,
    );

    $response = $connector->send($request);

    $mockClient->assertSent(FetchAPagebreakPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(PagebreakPositionDTO::class);
});
