<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\DiscountPositions\CreateEditDiscountPositionDTO;
use CodebarAg\Bexio\Dto\DiscountPositions\DiscountPositionDTO;
use CodebarAg\Bexio\Requests\DiscountPositions\CreateADiscountPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', function () {
    $mockClient = new MockClient([
        CreateADiscountPositionRequest::class => MockResponse::fixture('DiscountPositions/create-a-discount-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new CreateADiscountPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        position: new CreateEditDiscountPositionDTO(
            text: 'Test discount position',
            is_percentual: true,
            value: '10',
        )
    );

    $response = $connector->send($request);

    $mockClient->assertSent(CreateADiscountPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(DiscountPositionDTO::class);
});
