<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\Payments\FetchAListOfPaymentsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../../Fixtures/Saloon/Invoices/Payments/fetch-a-list-of-payments.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        FetchAListOfPaymentsRequest::class => MockResponse::fixture('Invoices/Payments/fetch-a-list-of-payments'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAListOfPaymentsRequest(invoice_id: 52));

    Saloon::assertSent(FetchAListOfPaymentsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class);
})->group('invoices');
