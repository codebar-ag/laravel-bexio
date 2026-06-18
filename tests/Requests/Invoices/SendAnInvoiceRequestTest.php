<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\SendAnInvoiceRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        SendAnInvoiceRequest::class => MockResponse::make(body: '{"success": true}', status: 200),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new SendAnInvoiceRequest(
        invoice_id: 1,
        payload: [
            'recipient_emails' => ['test@example.com'],
            'subject' => 'Invoice',
            'message' => 'Please find your invoice attached.',
            'mark_as_open' => true,
        ],
    ));

    Saloon::assertSent(SendAnInvoiceRequest::class);

    expect($response->json())->toHaveKey('success');
})->group('invoices');
