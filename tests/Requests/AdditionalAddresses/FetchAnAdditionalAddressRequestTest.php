<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\AdditionalAddresses\FetchAnAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        FetchAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/fetch-an-additional-addresses'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAnAdditionalAddressRequest(
        contactId: 1,
        id: 10,
    ));

    $mockClient->assertSent(FetchAnAdditionalAddressRequest::class);
});
