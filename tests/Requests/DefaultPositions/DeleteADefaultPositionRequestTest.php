<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\DefaultPositions\DeleteADefaultPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can delete a default position', function () {
    $mockClient = new MockClient([
        DeleteADefaultPositionRequest::class => MockResponse::fixture('DefaultPositions/delete-a-default-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new DeleteADefaultPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 2,
    );

    $response = $connector->send($request);

    $mockClient->assertSent(DeleteADefaultPositionRequest::class);
    expect($response->dto())->not()->toBeNull();
});
