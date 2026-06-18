<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\PaymentDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\Payments\FetchAPaymentRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../../Fixtures/Saloon/Invoices/Payments/fetch-a-payment.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        FetchAPaymentRequest::class => MockResponse::fixture('Invoices/Payments/fetch-a-payment'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAPaymentRequest(invoice_id: 52, payment_id: 1));

    Saloon::assertSent(FetchAPaymentRequest::class);

    expect($response->dto())->toBeInstanceOf(PaymentDTO::class);
})->group('invoices');
