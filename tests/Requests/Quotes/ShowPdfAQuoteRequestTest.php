<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Invoices\PdfDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Quotes\FetchAListOfQuotesRequest;
use CodebarAg\Bexio\Requests\Quotes\ShowPdfAQuoteRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Quotes/show-pdf-a-quote';

    if (shouldResetFixtures()) {
        @unlink($fixturePath.'/fetch-a-list-of-quotes.json');
        @unlink($fixturePath.'/show-pdf-a-quote.json');
    }

    Saloon::fake([
        ShowPdfAQuoteRequest::class => MockResponse::fixture('Quotes/show-pdf-a-quote/show-pdf-a-quote'),
        FetchAListOfQuotesRequest::class => MockResponse::fixture('Quotes/show-pdf-a-quote/fetch-a-list-of-quotes'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $quotesResponse = $connector->send(new FetchAListOfQuotesRequest);
    $existingQuote = $quotesResponse->dto()->first();

    if (! $existingQuote) {
        $this->markTestSkipped('No quotes found in the system to show PDF');
    }

    $response = $connector->send(new ShowPdfAQuoteRequest(quote_id: $existingQuote->id));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(PdfDTO::class);

    Saloon::assertSent(ShowPdfAQuoteRequest::class);
})->group('quotes');
