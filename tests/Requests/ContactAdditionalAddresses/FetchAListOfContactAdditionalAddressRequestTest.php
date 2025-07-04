<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactAdditionalAddresses\FetchAListOfContactAdditionalAddressesRequest;
use Illuminate\Support\Collection;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAListOfContactAdditionalAddressesRequest::class => MockResponse::fixture('ContactAdditionalAddresses/fetch-a-list-of-contact-additional-addresses'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAListOfContactAdditionalAddressesRequest(contactId: 1));

    Saloon::assertSent(FetchAListOfContactAdditionalAddressesRequest::class);

    expect($response->dto())->toBeInstanceOf(Collection::class)
        ->and($response->dto()->count())->toBe(2);
});
