<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Payments\PaymentDTO;
use CodebarAg\Bexio\Requests\Payments\CancelAPaymentRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        CancelAPaymentRequest::class => MockResponse::fixture('Payments/cancel-a-payment'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new CancelAPaymentRequest(
        payment_id: 1
    ));

    $mockClient->assertSent(CancelAPaymentRequest::class);

    expect($response->dto())->toBeInstanceOf(PaymentDTO::class);
})->skip('Have to wait untill 06/01/2024 to test this');
