<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\PagebreakPositions\DeleteAPagebreakPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can delete a pagebreak position', function () {
    $mockClient = new MockClient([
        DeleteAPagebreakPositionRequest::class => MockResponse::fixture('PagebreakPositions/delete-a-pagebreak-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new DeleteAPagebreakPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 2,
    );

    $response = $connector->send($request);

    $mockClient->assertSent(DeleteAPagebreakPositionRequest::class);
    expect($response->dto())->not()->toBeNull();
});
