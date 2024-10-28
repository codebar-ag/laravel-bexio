<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Invoices\DeleteAnInvoiceRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        DeleteAnInvoiceRequest::class => MockResponse::fixture('Invoices/delete-an-invoice'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new DeleteAnInvoiceRequest(
        invoice_id: 1,
    ));

    $mockClient->assertSent(DeleteAnInvoiceRequest::class);
});
