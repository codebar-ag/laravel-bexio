<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Payments\PaymentDTO;
use CodebarAg\Bexio\Requests\IbanPayments\GetIbanPaymentRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        GetIbanPaymentRequest::class => MockResponse::fixture('IbanPayments/get-iban-payment'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new GetIbanPaymentRequest(
        bank_account_id: 1,
        payment_id: 3
    ));

    Saloon::assertSent(GetIbanPaymentRequest::class);

    expect($response->dto())->toBeInstanceOf(PaymentDTO::class);
});
