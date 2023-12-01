<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Contacts\FetchAListOfContacts;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfContacts::class => MockResponse::fixture('fetch-a-list-of-contacts'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfContacts());

    ray($response->dto());

    $mockClient->assertSent(FetchAListOfContacts::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(5);
});
