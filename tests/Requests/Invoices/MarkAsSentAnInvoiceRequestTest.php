<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\MarkAsSentAnInvoiceRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        MarkAsSentAnInvoiceRequest::class => MockResponse::make(body: '{"success": true}', status: 200),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new MarkAsSentAnInvoiceRequest(invoice_id: 1));

    Saloon::assertSent(MarkAsSentAnInvoiceRequest::class);

    expect($response->json())->toHaveKey('success');
})->group('invoices');
