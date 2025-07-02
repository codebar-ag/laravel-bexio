<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Payments\PaymentDTO;
use CodebarAg\Bexio\Requests\Payments\CancelAPaymentRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        CancelAPaymentRequest::class => MockResponse::fixture('Payments/cancel-a-payment'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new CancelAPaymentRequest(
        payment_id: 5
    ));

    $mockClient->assertSent(CancelAPaymentRequest::class);

    expect($response->dto())->toBeInstanceOf(PaymentDTO::class);
});
