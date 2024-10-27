<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Invoices\CancelAnInvoiceRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        CancelAnInvoiceRequest::class => MockResponse::fixture('Invoices/cancel-an-invoice'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new CancelAnInvoiceRequest(
        invoiceId: 1,
    ));

    $mockClient->assertSent(CancelAnInvoiceRequest::class);
})->only();
