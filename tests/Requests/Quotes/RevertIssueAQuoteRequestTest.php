<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Quotes\FetchAListOfQuotesRequest;
use CodebarAg\Bexio\Requests\Quotes\RevertIssueAQuoteRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Quotes/revert-issue-a-quote.json';
    $listFixturePath = __DIR__.'/../../Fixtures/Saloon/Quotes/fetch-a-list-of-quotes.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($listFixturePath);
    }

    Saloon::fake([
        RevertIssueAQuoteRequest::class => MockResponse::fixture('Quotes/revert-issue-a-quote'),
        FetchAListOfQuotesRequest::class => MockResponse::fixture('Quotes/fetch-a-list-of-quotes'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $quotesResponse = $connector->send(new FetchAListOfQuotesRequest);
    $existingQuote = $quotesResponse->dto()->first();

    if (! $existingQuote) {
        $this->markTestSkipped('No quotes found in the system to revert issue');
    }

    $response = $connector->send(new RevertIssueAQuoteRequest(quote_id: $existingQuote->id));

    expect($response->successful())->toBeTrue();

    Saloon::assertSent(RevertIssueAQuoteRequest::class);
})->group('quotes');
