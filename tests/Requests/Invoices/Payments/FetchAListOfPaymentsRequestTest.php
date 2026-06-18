<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\FetchAListOfInvoicesRequest;
use CodebarAg\Bexio\Requests\Invoices\Payments\FetchAListOfPaymentsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    if (shouldResetFixtures()) {
        @unlink(__DIR__.'/../../../Fixtures/Saloon/Invoices/Payments/fetch-a-list-of-payments.json');
    }

    Saloon::fake([
        FetchAListOfInvoicesRequest::class => MockResponse::fixture('Invoices/fetch-a-list-of-invoices'),
        FetchAListOfPaymentsRequest::class => MockResponse::fixture('Invoices/Payments/fetch-a-list-of-payments'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $invoice = $connector->send(new FetchAListOfInvoicesRequest)->dto()->first();

    if ($invoice === null) {
        $this->markTestSkipped('No invoice available to fetch payments for.');
    }

    $response = $connector->send(new FetchAListOfPaymentsRequest(invoice_id: $invoice->id));

    Saloon::assertSent(FetchAListOfPaymentsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class);
})->group('invoices');
