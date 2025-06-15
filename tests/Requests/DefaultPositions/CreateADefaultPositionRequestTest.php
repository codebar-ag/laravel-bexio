<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\DefaultPositions\CreateEditDefaultPositionDTO;
use CodebarAg\Bexio\Dto\DefaultPositions\DefaultPositionDTO;
use CodebarAg\Bexio\Requests\DefaultPositions\CreateADefaultPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', function () {
    $mockClient = new MockClient([
        CreateADefaultPositionRequest::class => MockResponse::fixture('DefaultPositions/create-a-default-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $request = new CreateADefaultPositionRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        position: new CreateEditDefaultPositionDTO(
            amount: 1,
            unit_id: 1,
            account_id: 145,
            tax_id: 29,
            text: 'Test position',
            unit_price: 100,
            discount_in_percent: '0',
        )
    );

    $response = $connector->send($request);

    $mockClient->assertSent(CreateADefaultPositionRequest::class);
    expect($response->dto())->toBeInstanceOf(DefaultPositionDTO::class);
});
