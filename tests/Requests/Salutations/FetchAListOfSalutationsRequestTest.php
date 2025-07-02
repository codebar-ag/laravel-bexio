<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Salutations\FetchAListOfSalutationsRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAListOfSalutationsRequest::class => MockResponse::fixture('Salutations/fetch-a-list-of-salutations'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAListOfSalutationsRequest);

    $mockClient->assertSent(FetchAListOfSalutationsRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(4);
});
