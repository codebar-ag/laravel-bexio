<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\AdditionalAddresses\FetchAListOfAdditionalAddressesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAListOfAdditionalAddressesRequest::class => MockResponse::fixture('AdditionalAddresses/fetch-a-list-of-additional-addresses'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAListOfAdditionalAddressesRequest(
        contactId: 1,
    ));

    Saloon::assertSent(FetchAListOfAdditionalAddressesRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(0);
});
