<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Contacts\SearchContactsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        SearchContactsRequest::class => MockResponse::fixture('Contacts/search-contacts'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SearchContactsRequest('name_1', 'JohnRestore'));

    $mockClient->assertSent(SearchContactsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(0);
});
