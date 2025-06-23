<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ItemPositions\FetchAListOfItemPositionsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', function () {
    $mockClient = new MockClient([
        FetchAListOfItemPositionsRequest::class => MockResponse::fixture('ItemPositions/fetch-a-list-of-item-positions'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfItemPositionsRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        limit: 2,
    ));

    $mockClient->assertSent(FetchAListOfItemPositionsRequest::class);
    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(2);
});
