<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Payments\DeleteAPaymentRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        DeleteAPaymentRequest::class => MockResponse::fixture('Payments/delete-a-payment'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new DeleteAPaymentRequest(
        payment_id: 1
    ));

    $mockClient->assertSent(DeleteAPaymentRequest::class);
});
