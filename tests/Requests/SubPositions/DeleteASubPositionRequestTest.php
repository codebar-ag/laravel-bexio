<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\SubPositions\DeleteASubPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can delete a sub position', function () {
    $mockClient = new MockClient([
        DeleteASubPositionRequest::class => MockResponse::fixture('SubPositions/delete-a-sub-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new DeleteASubPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 2,
    );

    $response = $connector->send($request);

    $mockClient->assertSent(DeleteASubPositionRequest::class);
    expect($response->dto())->not()->toBeNull();
});
