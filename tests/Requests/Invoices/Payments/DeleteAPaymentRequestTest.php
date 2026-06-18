<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\Payments\DeleteAPaymentRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        DeleteAPaymentRequest::class => MockResponse::make(body: '{"success": true}', status: 200),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new DeleteAPaymentRequest(invoice_id: 52, payment_id: 1));

    Saloon::assertSent(DeleteAPaymentRequest::class);

    expect($response->json())->toHaveKey('success');
})->group('invoices');
