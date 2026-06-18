<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ItemPositions\FetchAListOfItemPositionsRequest;
use CodebarAg\Bexio\Requests\Quotes\FetchAListOfQuotesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/ItemPositions/fetch-a-list-of-item-positions.json';
    $quotesFixturePath = __DIR__.'/../../Fixtures/Saloon/Quotes/fetch-a-list-of-quotes.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($quotesFixturePath);
    }

    Saloon::fake([
        FetchAListOfItemPositionsRequest::class => MockResponse::fixture('ItemPositions/fetch-a-list-of-item-positions'),
        FetchAListOfQuotesRequest::class => MockResponse::fixture('Quotes/fetch-a-list-of-quotes'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $quote = $connector->send(new FetchAListOfQuotesRequest)->dto()->first();

    if (! $quote) {
        $this->markTestSkipped('No quote (kb_offer) available to list item positions for');
    }

    $response = $connector->send(new FetchAListOfItemPositionsRequest(
        kb_document_type: 'kb_offer',
        document_id: $quote->id,
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(Collection::class);

    Saloon::assertSent(FetchAListOfItemPositionsRequest::class);
})->group('item-positions');
