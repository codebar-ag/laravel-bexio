<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ContactSectors\SearchContactSectorsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        SearchContactSectorsRequest::class => MockResponse::fixture('ContactSectors/search-contact-sectors'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new SearchContactSectorsRequest('name', 'Sector1'));

    $mockClient->assertSent(SearchContactSectorsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(1);
});
