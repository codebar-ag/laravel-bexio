<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\IbanPayments\CreateEditIbanPaymentDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Payments\PaymentDTO;
use CodebarAg\Bexio\Requests\IbanPayments\CreateIbanPaymentRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CreateIbanPaymentRequest::class => MockResponse::fixture('IbanPayments/create-iban-payment'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CreateIbanPaymentRequest(
        bank_account_id: 1,
        data: new CreateEditIbanPaymentDTO(
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
            iban: 'CH8100700110005554634',
            execution_date: '2024-01-08',
            is_salary_payment: false,
            is_editing_restricted: false,
            message: 'Rechnung 1234',
            allowance_type: 'no_fee',
        )
    ));

    Saloon::assertSent(CreateIbanPaymentRequest::class);

    expect($response->dto())->toBeInstanceOf(PaymentDTO::class);
});
