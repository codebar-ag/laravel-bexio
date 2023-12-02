<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\Contacts\SearchContactsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        SearchContactsRequest::class => MockResponse::fixture('Contacts/search-contacts'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SearchContactsRequest('name_1', 'JohnRestore'));

    $mockClient->assertSent(SearchContactsRequest::class);
});
