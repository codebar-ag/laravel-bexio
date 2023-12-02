<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ContactRelations\SearchContactRelationsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        SearchContactRelationsRequest::class => MockResponse::fixture('ContactRelations/search-contact-relations'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SearchContactRelationsRequest('description', 'This is a test'));

    $mockClient->assertSent(SearchContactRelationsRequest::class);
});
