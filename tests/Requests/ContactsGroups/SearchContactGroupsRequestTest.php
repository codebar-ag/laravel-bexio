<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ContactGroups\SearchContactGroupsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        SearchContactGroupsRequest::class => MockResponse::fixture('ContactGroups/search-contact-groups'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SearchContactGroupsRequest('name', 'Test'));

    $mockClient->assertSent(SearchContactGroupsRequest::class);
});
