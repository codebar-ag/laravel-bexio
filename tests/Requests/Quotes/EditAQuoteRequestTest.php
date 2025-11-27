<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Quotes\QuoteDTO;
use CodebarAg\Bexio\Requests\Quotes\EditAQuoteRequest;
use CodebarAg\Bexio\Requests\Quotes\FetchAListOfQuotesRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Quotes/edit-a-quote.json';
    $listFixturePath = __DIR__.'/../../Fixtures/Saloon/Quotes/fetch-a-list-of-quotes.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($listFixturePath);
    }

    Saloon::fake([
        EditAQuoteRequest::class => MockResponse::fixture('Quotes/edit-a-quote'),
        FetchAListOfQuotesRequest::class => MockResponse::fixture('Quotes/fetch-a-list-of-quotes'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $quotesResponse = $connector->send(new FetchAListOfQuotesRequest);
    $existingQuote = $quotesResponse->dto()->first();

    if (! $existingQuote) {
        $this->markTestSkipped('No quotes found in the system to edit');
    }

    $quote = QuoteDTO::fromArray([
        'id' => $existingQuote->id,
        'title' => 'Updated Quote Title',
        'contact_id' => $existingQuote->contact_id,
        'user_id' => $existingQuote->user_id,
        'pr_project_id' => $existingQuote->pr_project_id,
        'language_id' => $existingQuote->language_id,
        'bank_account_id' => $existingQuote->bank_account_id,
        'currency_id' => $existingQuote->currency_id,
        'payment_type_id' => $existingQuote->payment_type_id,
        'mwst_type' => $existingQuote->mwst_type,
        'mwst_is_net' => $existingQuote->mwst_is_net,
        'show_position_taxes' => $existingQuote->show_position_taxes,
        'is_valid_from' => $existingQuote->is_valid_from,
        'is_valid_to' => $existingQuote->is_valid_to,
        'api_reference' => $existingQuote->api_reference,
    ]);

    $response = $connector->send(new EditAQuoteRequest(
        quote_id: $existingQuote->id,
        quote: $quote
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(QuoteDTO::class);

    Saloon::assertSent(EditAQuoteRequest::class);
})->group('quotes');
