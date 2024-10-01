<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Projects\FetchAListOfProjectsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfProjectsRequest::class => MockResponse::fixture('Projects/fetch-a-list-of-projects'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfProjectsRequest);

    $mockClient->assertSent(FetchAListOfProjectsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(1);
});
