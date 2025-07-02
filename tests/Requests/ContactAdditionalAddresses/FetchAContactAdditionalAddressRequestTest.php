<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactAdditionalAddresses\FetchAContactAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAContactAdditionalAddressRequest::class => MockResponse::fixture('ContactAdditionalAddresses/fetch-a-contact-additional-address'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAContactAdditionalAddressRequest(contactId: 1, id: 10));

    $mockClient->assertSent(FetchAContactAdditionalAddressRequest::class);
});
