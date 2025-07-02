<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Payments\PaymentDTO;
use CodebarAg\Bexio\Dto\QrPayments\CreateEditQrPaymentDTO;
use CodebarAg\Bexio\Requests\QrPayments\CreateQrPaymentRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CreateQrPaymentRequest::class => MockResponse::fixture('QrPayments/create-qr-payment'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CreateQrPaymentRequest(
        bank_account_id: 1,
        data: new CreateEditQrPaymentDTO(
            instructed_amount: [
                'currency' => 'CHF',
                'amount' => 100,
            ],
            recipient: [
                'name' => 'Müller GmbH',
                'street' => 'Sonnenstrasse',
                'zip' => 8005,
                'city' => 'Zürich',
                'country_code' => 'CH',
                'house_number' => 36,
            ],
            execution_date: '2024-01-08',
            iban: 'CH8100700110005554634',
            qr_reference_nr: null,
            additional_information: null,
            is_editing_restricted: false,
        )
    ));

    Saloon::assertSent(CreateQrPaymentRequest::class);

    expect($response->dto())->toBeInstanceOf(PaymentDTO::class);
});
