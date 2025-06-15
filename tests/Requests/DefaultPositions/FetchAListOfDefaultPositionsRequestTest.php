<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\DefaultPositions\DefaultPositionDTO;
use CodebarAg\Bexio\Requests\DefaultPositions\FetchAListOfDefaultPositionsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfDefaultPositionsRequest::class => MockResponse::fixture('DefaultPositions/fetch-a-list-of-default-positions'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfDefaultPositionsRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        limit: 2,
    ));

    $mockClient->assertSent(FetchAListOfDefaultPositionsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(2);
});
