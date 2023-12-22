<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Titles\FetchAListOfTitlesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfTitlesRequest::class => MockResponse::fixture('Titles/fetch-a-list-of-titles'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfTitlesRequest());

    $mockClient->assertSent(FetchAListOfTitlesRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(3);
});
