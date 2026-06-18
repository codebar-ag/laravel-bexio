<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\PaymentDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\Payments\CreateAPaymentRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../../Fixtures/Saloon/Invoices/Payments/create-a-payment.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        CreateAPaymentRequest::class => MockResponse::fixture('Invoices/Payments/create-a-payment'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $payment = PaymentDTO::fromArray([
        'id' => null,
        'date' => '2024-10-28',
        'value' => '100.00',
        'bank_account_id' => 1,
        'payment_service_id' => null,
        'is_cash_discount' => false,
    ]);

    $response = $connector->send(new CreateAPaymentRequest(invoice_id: 52, payment: $payment));

    Saloon::assertSent(CreateAPaymentRequest::class);

    expect($response->dto())->toBeInstanceOf(PaymentDTO::class);
})->group('invoices');
