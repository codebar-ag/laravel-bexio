<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\SubtotalPositions\FetchAListOfSubtotalPositionsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', function () {
    $mockClient = new MockClient([
        FetchAListOfSubtotalPositionsRequest::class => MockResponse::fixture('SubtotalPositions/fetch-a-list-of-subtotal-positions'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfSubtotalPositionsRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        limit: 2,
    ));

    $mockClient->assertSent(FetchAListOfSubtotalPositionsRequest::class);
    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(2);
});
