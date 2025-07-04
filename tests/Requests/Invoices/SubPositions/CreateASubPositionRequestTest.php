<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\InvoicePositionDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\SubPositions\CreateASubPositionRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CreateASubPositionRequest::class => MockResponse::fixture('Invoices/SubPositions/create-a-sub-position'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

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

    Saloon::assertSent(CreateASubPositionRequest::class);

    expect($response->dto())->toBeInstanceOf(InvoicePositionDTO::class);
});
