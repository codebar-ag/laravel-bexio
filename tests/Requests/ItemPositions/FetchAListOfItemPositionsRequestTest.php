<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ItemPositions\FetchAListOfItemPositionsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/ItemPositions/fetch-a-list-of-item-positions.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        FetchAListOfItemPositionsRequest::class => MockResponse::fixture('ItemPositions/fetch-a-list-of-item-positions'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $response = $connector->send(new FetchAListOfItemPositionsRequest(
        kb_document_id: 1,
        kb_document_type: 'kb_offer'
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(Collection::class);

    Saloon::assertSent(FetchAListOfItemPositionsRequest::class);
})->group('item-positions');
