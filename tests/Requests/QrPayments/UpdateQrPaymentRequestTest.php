<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Payments\PaymentDTO;
use CodebarAg\Bexio\Dto\QrPayments\CreateEditQrPaymentDTO;
use CodebarAg\Bexio\Requests\QrPayments\EditQrPaymentRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        //        EditQrPaymentRequest::class => MockResponse::fixture('QrPayments/update-qr-payment'),
    ]);

    $connector = new BexioConnector;
    //    $connector->withMockClient($mockClient);

    $response = $connector->send(new EditQrPaymentRequest(
        bank_account_id: 1,
        payment_id: 4,
        iban: '8100700110005554634',
        id: 4,
        data: new CreateEditQrPaymentDTO(
            instructed_amount: [
                'currency' => 'CHF',
                'amount' => 100,
            ],
            recipient: [
                'name' => 'Müller GmbH',
                'street' => 'Colchester Place',
                'zip' => 8005,
                'city' => 'Zürich',
                'country_code' => 'CH',
                'house_number' => 36,
            ],
            execution_date: '2024-01-08',
            iban: 'CH8100700110005554634',
        )
    ));

    $mockClient->assertSent(EditQrPaymentRequest::class);

    expect($response->dto())->toBeInstanceOf(PaymentDTO::class);
})->skip('TODO: Fix this test');
