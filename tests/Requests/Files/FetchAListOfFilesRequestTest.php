<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Files\FetchAListOfFilesRequest;
use CodebarAg\Bexio\Requests\Salutations\FetchAListOfSalutationsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfFilesRequest::class => MockResponse::fixture('Files/fetch-a-list-of-files'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfFilesRequest());

    ray($response->dto());

    $mockClient->assertSent(FetchAListOfFilesRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(6);
});
