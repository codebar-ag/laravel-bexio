<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ContactAdditionalAddresses\SearchContactAdditionalAddressesRequest;
use CodebarAg\Bexio\Requests\ContactRelations\SearchContactRelationsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        SearchContactAdditionalAddressesRequest::class => MockResponse::fixture('ContactAdditionalAddresses/search-contact-additional-addresses'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SearchContactAdditionalAddressesRequest(1, 'name', 'Test'));

    $mockClient->assertSent(SearchContactAdditionalAddressesRequest::class);
});
