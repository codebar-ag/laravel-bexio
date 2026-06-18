<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\PdfDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Invoices\FetchAListOfInvoicesRequest;
use CodebarAg\Bexio\Requests\Invoices\ShowPdfRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    if (shouldResetFixtures()) {
        @unlink(__DIR__.'/../../Fixtures/Saloon/Invoices/show-pdf.json');
    }

    Saloon::fake([
        FetchAListOfInvoicesRequest::class => MockResponse::fixture('Invoices/fetch-a-list-of-invoices'),
        ShowPdfRequest::class => MockResponse::fixture('Invoices/show-pdf'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $invoice = $connector->send(new FetchAListOfInvoicesRequest)->dto()->first();

    if ($invoice === null) {
        $this->markTestSkipped('No invoice available to render as PDF.');
    }

    $response = $connector->send(new ShowPdfRequest(invoice_id: $invoice->id));

    Saloon::assertSent(ShowPdfRequest::class);

    expect($response->dto())->toBeInstanceOf(PdfDTO::class);
})->group('invoices');
