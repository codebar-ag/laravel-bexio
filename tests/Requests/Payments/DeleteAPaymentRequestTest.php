<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Payments\DeleteAPaymentRequest;
use CodebarAg\Bexio\Requests\Payments\FetchAListOfPaymentsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Payments/delete-a-payment.json';
    $listFixturePath = __DIR__.'/../../Fixtures/Saloon/Payments/fetch-a-list-of-payments.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($listFixturePath);
    }

    Saloon::fake([
        DeleteAPaymentRequest::class => MockResponse::fixture('Payments/delete-a-payment'),
        FetchAListOfPaymentsRequest::class => MockResponse::fixture('Payments/fetch-a-list-of-payments'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $existingPayment = $connector->send(new FetchAListOfPaymentsRequest)->dto()->first();

    if (! $existingPayment) {
        $this->markTestSkipped('No payments available to delete');
    }

    $response = $connector->send(new DeleteAPaymentRequest(payment_id: $existingPayment->id));

    expect($response->successful())->toBeTrue();

    Saloon::assertSent(DeleteAPaymentRequest::class);
})->group('payments');
