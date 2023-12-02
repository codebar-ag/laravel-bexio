<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ContactAdditionalAddresses\FetchAListOfContactAdditionalAddressesRequest;
use CodebarAg\Bexio\Requests\ContactRelations\FetchAListOfContactRelationsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfContactAdditionalAddressesRequest::class => MockResponse::fixture('ContactAdditionalAddresses/fetch-a-list-of-contact-additional-addresses'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfContactAdditionalAddressesRequest(contactId: 1));

    $mockClient->assertSent(FetchAListOfContactAdditionalAddressesRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(1);
});
