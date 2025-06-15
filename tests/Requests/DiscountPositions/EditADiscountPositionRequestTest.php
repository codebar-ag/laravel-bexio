<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\DiscountPositions\CreateEditDiscountPositionDTO;
use CodebarAg\Bexio\Dto\DiscountPositions\DiscountPositionDTO;
use CodebarAg\Bexio\Requests\DiscountPositions\EditADiscountPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can edit a discount position', function () {
    $mockClient = new MockClient([
        EditADiscountPositionRequest::class => MockResponse::fixture('DiscountPositions/edit-a-discount-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new EditADiscountPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 1,
        position: new CreateEditDiscountPositionDTO(
            text: 'Test discount position',
            is_percentual: true,
            value: '50',
        ),
    );

    $response = $connector->send($request);

    $mockClient->assertSent(EditADiscountPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(DiscountPositionDTO::class);
});
