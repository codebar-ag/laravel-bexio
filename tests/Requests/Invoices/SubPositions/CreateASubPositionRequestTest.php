<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\InvoicePositionDTO;
use CodebarAg\Bexio\Requests\Invoices\SubPositions\CreateASubPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        CreateASubPositionRequest::class => MockResponse::fixture('Invoices/SubPositions/create-a-sub-position'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $position = InvoicePositionDTO::fromArray([
        'type' => 'KbSubPosition',
        'text' => Str::uuid(),
        'show_pos_nr' => true,
    ]);

    $response = $connector->send(new CreateASubPositionRequest(
        kb_document_type: 'kb_invoice',
        invoice_id: 53,
        position: $position,
    ));

    $mockClient->assertSent(CreateASubPositionRequest::class);

    expect($response->dto())->toBeInstanceOf(InvoicePositionDTO::class);
});
