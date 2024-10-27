<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\BusinessActivities\FetchAListOfBusinessActivitesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfBusinessActivitesRequest::class => MockResponse::fixture('BusinessActivities/fetch-a-list-of-business-activities'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfBusinessActivitesRequest);

    ray($response->json());

    $mockClient->assertSent(FetchAListOfBusinessActivitesRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(5);
});
