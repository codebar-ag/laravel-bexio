<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ContactSectors\SearchContactSectorsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        SearchContactSectorsRequest::class => MockResponse::fixture('ContactSectors/search-contact-sectors'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SearchContactSectorsRequest('name', 'Sector1'));

    $mockClient->assertSent(SearchContactSectorsRequest::class);
});
