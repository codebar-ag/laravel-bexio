<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\TextPositions\DeleteATextPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can delete a text position', function () {
    $mockClient = new MockClient([
        DeleteATextPositionRequest::class => MockResponse::fixture('TextPositions/delete-a-text-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new DeleteATextPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 1,
    );

    $response = $connector->send($request);

    $mockClient->assertSent(DeleteATextPositionRequest::class);
    expect($response->dto())->not()->toBeNull();
});
