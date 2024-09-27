<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Languages\FetchAListOfLanguagesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfLanguagesRequest::class => MockResponse::fixture('Languages/fetch-a-list-of-languages'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfLanguagesRequest);

    ray($response->json());

    $mockClient->assertSent(FetchAListOfLanguagesRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(0);
})->skip('WIP');