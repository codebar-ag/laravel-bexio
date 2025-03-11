<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use CodebarAg\Bexio\Requests\Invoices\EditAnInvoiceRequest;
use CodebarAg\Bexio\Requests\Invoices\FetchAnInvoiceRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

it('can perform the request', closure: function () {
    $mockClient = MockClient::global([
        EditAnInvoiceRequest::class => MockResponse::fixture('Invoices/edit-an-invoice'),
        FetchAnInvoiceRequest::class => MockResponse::fixture('Invoices/fetch-an-invoice-edit'),
    ]);

    $connector = new BexioConnector;

    $invoice = $connector->send(new FetchAnInvoiceRequest(invoice_id: 53))->dto();

    expect($invoice)->toBeInstanceOf(InvoiceDTO::class)
        ->and($invoice->title)->toBe('Test');

    $invoice->title = 'Test Invoice';

    $response = $connector->send(new EditAnInvoiceRequest(invoice_id: 53, invoice: $invoice));

    $mockClient->assertSent(EditAnInvoiceRequest::class);

    expect($response->dto())->toBeInstanceOf(InvoiceDTO::class)
        ->and($response->dto()->title)->toBe('Test Invoice');
});
