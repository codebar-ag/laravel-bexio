<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ContactRelations\FetchAListOfContactRelationsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfContactRelationsRequest::class => MockResponse::fixture('ContactRelations/fetch-a-list-of-contact-relations'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfContactRelationsRequest);

    $mockClient->assertSent(FetchAListOfContactRelationsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(0);
});
