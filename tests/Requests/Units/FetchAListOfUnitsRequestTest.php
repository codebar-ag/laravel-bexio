<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Units\FetchAListOfUnitsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfUnitsRequest::class => MockResponse::fixture('Units/fetch-a-list-of-units'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfUnitsRequest);

    $mockClient->assertSent(FetchAListOfUnitsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(2);
});
