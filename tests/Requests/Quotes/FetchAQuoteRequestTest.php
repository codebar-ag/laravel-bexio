<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Quotes\QuoteDTO;
use CodebarAg\Bexio\Requests\Quotes\FetchAListOfQuotesRequest;
use CodebarAg\Bexio\Requests\Quotes\FetchAQuoteRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Quotes/fetch-a-quote';

    if (shouldResetFixtures()) {
        @unlink($fixturePath.'/fetch-a-list-of-quotes.json');
        @unlink($fixturePath.'/fetch-a-quote.json');
    }

    Saloon::fake([
        FetchAQuoteRequest::class => MockResponse::fixture('Quotes/fetch-a-quote/fetch-a-quote'),
        FetchAListOfQuotesRequest::class => MockResponse::fixture('Quotes/fetch-a-quote/fetch-a-list-of-quotes'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $quotesResponse = $connector->send(new FetchAListOfQuotesRequest);
    $existingQuote = $quotesResponse->dto()->first();

    if (! $existingQuote) {
        $this->markTestSkipped('No quotes found in the system to fetch');
    }

    $response = $connector->send(new FetchAQuoteRequest(quote_id: $existingQuote->id));

    ray($response->dto());
    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(QuoteDTO::class);

    Saloon::assertSent(FetchAQuoteRequest::class);
})->group('quotes');
