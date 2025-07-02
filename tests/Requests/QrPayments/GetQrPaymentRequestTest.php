<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Payments\PaymentDTO;
use CodebarAg\Bexio\Requests\QrPayments\GetQrPaymentRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        GetQrPaymentRequest::class => MockResponse::fixture('QrPayments/get-a-qe-payment'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new GetQrPaymentRequest(
        bank_account_id: 1,
        payment_id: 4
    ));

    Saloon::assertSent(GetQrPaymentRequest::class);

    expect($response->dto())->toBeInstanceOf(PaymentDTO::class);
});
