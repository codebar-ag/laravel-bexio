<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\TextPositions\TextPositionDTO;
use CodebarAg\Bexio\Requests\TextPositions\FetchAListOfTextPositionsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', function () {
    $mockClient = new MockClient([
        FetchAListOfTextPositionsRequest::class => MockResponse::fixture('TextPositions/fetch-a-list-of-text-positions'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfTextPositionsRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        limit: 2,
    ));

    $mockClient->assertSent(FetchAListOfTextPositionsRequest::class);
    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(2);
});
