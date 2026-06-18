<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\CopyAnInvoiceRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Invoices/copy-an-invoice.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        CopyAnInvoiceRequest::class => MockResponse::fixture('Invoices/copy-an-invoice'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CopyAnInvoiceRequest(invoice_id: 52));

    Saloon::assertSent(CopyAnInvoiceRequest::class);

    expect($response->dto())->toBeInstanceOf(InvoiceDTO::class);
})->group('invoices');
