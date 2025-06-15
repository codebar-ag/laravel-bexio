<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\DefaultPositions\CreateEditDefaultPositionDTO;
use CodebarAg\Bexio\Dto\DefaultPositions\DefaultPositionDTO;
use CodebarAg\Bexio\Requests\DefaultPositions\EditADefaultPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can edit a default position', function () {
    $mockClient = new MockClient([
        EditADefaultPositionRequest::class => MockResponse::fixture('DefaultPositions/edit-a-default-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new EditADefaultPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        positionId: 10,
        position: new CreateEditDefaultPositionDTO(
            amount: 1,
            unit_id: 1,
            account_id: 145,
            tax_id: 29,
            text: 'Test position',
            unit_price: 150,
            discount_in_percent: '0',
        ),
    );

    $response = $connector->send($request);

    $mockClient->assertSent(EditADefaultPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(DefaultPositionDTO::class);
});
