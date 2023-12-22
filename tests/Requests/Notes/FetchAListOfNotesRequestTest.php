<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Notes\FetchAListOfNotesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfNotesRequest::class => MockResponse::fixture('Notes/fetch-a-list-of-notes'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfNotesRequest());

    $mockClient->assertSent(FetchAListOfNotesRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(2);
});
