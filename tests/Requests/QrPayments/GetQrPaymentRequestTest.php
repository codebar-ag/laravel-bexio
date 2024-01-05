<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Payments\PaymentDTO;
use CodebarAg\Bexio\Requests\BankAccounts\FetchAListOfBankAccountsRequest;
use CodebarAg\Bexio\Requests\QrPayments\GetQrPaymentRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
        $mockClient = new MockClient([
            GetQrPaymentRequest::class => MockResponse::fixture('QrPayments/get-a-qe-payment'),
        ]);

    $connector = new BexioConnector;
        $connector->withMockClient($mockClient);

    $response = $connector->send(new GetQrPaymentRequest(
        bank_account_id: 1,
        payment_id: 4
    ));

    ray($response->json());

        $mockClient->assertSent(GetQrPaymentRequest::class);

    expect($response->dto())->toBeInstanceOf(PaymentDTO::class);
});
