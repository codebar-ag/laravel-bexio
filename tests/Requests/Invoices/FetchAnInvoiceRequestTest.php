<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\InvoiceDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\FetchAnInvoiceRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAnInvoiceRequest::class => MockResponse::fixture('Invoices/fetch-an-invoice'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAnInvoiceRequest(52));

    Saloon::assertSent(FetchAnInvoiceRequest::class);

    expect($response->dto())->toBeInstanceOf(InvoiceDTO::class);
});
