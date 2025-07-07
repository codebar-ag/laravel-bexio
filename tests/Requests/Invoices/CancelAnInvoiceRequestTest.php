<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\CancelAnInvoiceRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CancelAnInvoiceRequest::class => MockResponse::fixture('Invoices/cancel-an-invoice'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CancelAnInvoiceRequest(
        invoice_id: 1,
    ));

    Saloon::assertSent(CancelAnInvoiceRequest::class);
});
