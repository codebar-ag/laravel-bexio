<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Quotes\FetchAListOfQuotesRequest;
use CodebarAg\Bexio\Requests\Quotes\MarkAsSentAQuoteRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Quotes/mark-as-sent-a-quote';

    if (shouldResetFixtures()) {
        @unlink($fixturePath.'/fetch-a-list-of-quotes.json');
        @unlink($fixturePath.'/mark-as-sent-a-quote.json');
    }

    Saloon::fake([
        MarkAsSentAQuoteRequest::class => MockResponse::fixture('Quotes/mark-as-sent-a-quote/mark-as-sent-a-quote'),
        FetchAListOfQuotesRequest::class => MockResponse::fixture('Quotes/mark-as-sent-a-quote/fetch-a-list-of-quotes'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $quotesResponse = $connector->send(new FetchAListOfQuotesRequest);
    $existingQuote = $quotesResponse->dto()->first();

    if (! $existingQuote) {
        $this->markTestSkipped('No quotes found in the system to mark as sent');
    }

    $response = $connector->send(new MarkAsSentAQuoteRequest(quote_id: $existingQuote->id));

    expect($response->successful())->toBeTrue();

    Saloon::assertSent(MarkAsSentAQuoteRequest::class);
})->group('quotes');
