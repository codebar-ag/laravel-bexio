<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactRelations\SearchContactRelationsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        SearchContactRelationsRequest::class => MockResponse::fixture('ContactRelations/search-contact-relations'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new SearchContactRelationsRequest('contact_id', 2));

    Saloon::assertSent(SearchContactRelationsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(0);
});
