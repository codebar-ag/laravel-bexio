<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Payments\FetchAListOfPaymentsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfPaymentsRequest::class => MockResponse::fixture('Payments/fetch-a-list-of-payments'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfPaymentsRequest());

    ray($response->json());
    ray($response->dto());

    $mockClient->assertSent(FetchAListOfPaymentsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(1);
});
