<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\DiscountPositions\DiscountPositionDTO;
use CodebarAg\Bexio\Requests\DiscountPositions\FetchADiscountPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can fetch a discount position', function () {
    $mockClient = new MockClient([
        FetchADiscountPositionRequest::class => MockResponse::fixture('DiscountPositions/fetch-a-discount-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new FetchADiscountPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 1,
    );

    $response = $connector->send($request);

    $mockClient->assertSent(FetchADiscountPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(DiscountPositionDTO::class);
});
