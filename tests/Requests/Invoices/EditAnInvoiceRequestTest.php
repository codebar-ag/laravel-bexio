<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use CodebarAg\Bexio\Requests\Invoices\EditAnInvoiceRequest;
use CodebarAg\Bexio\Requests\Invoices\FetchAnInvoiceRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        EditAnInvoiceRequest::class => MockResponse::fixture('Invoices/edit-an-invoice'),
        FetchAnInvoiceRequest::class => MockResponse::fixture('Invoices/fetch-an-invoice-edit'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $invoice = $connector->send(new FetchAnInvoiceRequest(invoice_id: 53))->dto();

    expect($invoice)->toBeInstanceOf(InvoiceDTO::class)
        ->and($invoice->title)->toBe('Test');

    $invoice->title = 'Test Invoice';

    $response = $connector->send(new EditAnInvoiceRequest(invoice_id: 53, invoice: $invoice));

    $mockClient->assertSent(EditAnInvoiceRequest::class);

    expect($response->dto())->toBeInstanceOf(InvoiceDTO::class)
        ->and($response->dto()->title)->toBe('Test Invoice');
});
