<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\DiscountPositions\FetchAListOfDiscountPositionsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', function () {
    $mockClient = new MockClient([
        FetchAListOfDiscountPositionsRequest::class => MockResponse::fixture('DiscountPositions/fetch-a-list-of-discount-positions'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfDiscountPositionsRequest(
        kbDocumentType: 'kb_invoice',
        documentId: 2,
        limit: 2,
    ));

    $mockClient->assertSent(FetchAListOfDiscountPositionsRequest::class);
    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(2);
});
