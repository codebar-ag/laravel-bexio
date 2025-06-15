<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\PagebreakPositions\PagebreakPositionDTO;
use CodebarAg\Bexio\Requests\PagebreakPositions\FetchAListOfPagebreakPositionsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', function () {
    $mockClient = new MockClient([
        FetchAListOfPagebreakPositionsRequest::class => MockResponse::fixture('PagebreakPositions/fetch-a-list-of-pagebreak-positions'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfPagebreakPositionsRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        limit: 2,
    ));

    $mockClient->assertSent(FetchAListOfPagebreakPositionsRequest::class);
    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(2);
});
