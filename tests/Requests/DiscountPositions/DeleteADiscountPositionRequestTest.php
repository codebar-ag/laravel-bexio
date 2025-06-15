<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\DiscountPositions\DeleteADiscountPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can delete a discount position', function () {
    $mockClient = new MockClient([
        DeleteADiscountPositionRequest::class => MockResponse::fixture('DiscountPositions/delete-a-discount-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new DeleteADiscountPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 3,
    );

    $response = $connector->send($request);

    $mockClient->assertSent(DeleteADiscountPositionRequest::class);
    expect($response->dto())->not()->toBeNull();
});
