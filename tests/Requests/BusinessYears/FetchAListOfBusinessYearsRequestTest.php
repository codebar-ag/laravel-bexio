<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\BusinessYears\FetchAListOfBusinessYearsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfBusinessYearsRequest::class => MockResponse::fixture('BusinessYears/fetch-a-list-of-business-years'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfBusinessYearsRequest());

    $mockClient->assertSent(FetchAListOfBusinessYearsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(2);
});
