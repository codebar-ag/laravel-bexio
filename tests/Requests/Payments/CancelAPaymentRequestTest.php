<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Payments\PaymentDTO;
use CodebarAg\Bexio\Requests\Payments\CancelAPaymentRequest;
use CodebarAg\Bexio\Requests\Payments\FetchAListOfPaymentsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Payments/cancel-a-payment.json';
    $listFixturePath = __DIR__.'/../../Fixtures/Saloon/Payments/fetch-a-list-of-payments.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($listFixturePath);
    }

    Saloon::fake([
        CancelAPaymentRequest::class => MockResponse::fixture('Payments/cancel-a-payment'),
        FetchAListOfPaymentsRequest::class => MockResponse::fixture('Payments/fetch-a-list-of-payments'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $existingPayment = $connector->send(new FetchAListOfPaymentsRequest)->dto()->first();

    if (! $existingPayment) {
        $this->markTestSkipped('No payments available to cancel');
    }

    $response = $connector->send(new CancelAPaymentRequest(payment_id: $existingPayment->id));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(PaymentDTO::class);

    Saloon::assertSent(CancelAPaymentRequest::class);
})->group('payments');
