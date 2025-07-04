<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Payments\DeleteAPaymentRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        DeleteAPaymentRequest::class => MockResponse::fixture('Payments/delete-a-payment'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new DeleteAPaymentRequest(
        payment_id: 4,
    ));

    Saloon::assertSent(DeleteAPaymentRequest::class);
});
