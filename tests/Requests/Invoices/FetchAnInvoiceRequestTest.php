<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use CodebarAg\Bexio\Requests\Invoices\FetchAnInvoiceRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAnInvoiceRequest::class => MockResponse::fixture('Invoices/fetch-an-invoice'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAnInvoiceRequest(52));

    $mockClient->assertSent(FetchAnInvoiceRequest::class);

    expect($response->dto())->toBeInstanceOf(InvoiceDTO::class);
});
