<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\DeleteAnInvoiceRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        DeleteAnInvoiceRequest::class => MockResponse::fixture('Invoices/delete-an-invoice'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new DeleteAnInvoiceRequest(
        invoice_id: 1,
    ));

    Saloon::assertSent(DeleteAnInvoiceRequest::class);
});
