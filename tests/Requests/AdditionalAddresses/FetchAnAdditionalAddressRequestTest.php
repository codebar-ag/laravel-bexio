<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\AdditionalAddresses\FetchAnAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/fetch-an-additional-addresses'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAnAdditionalAddressRequest(
        contactId: 1,
        id: 10,
    ));

    Saloon::assertSent(FetchAnAdditionalAddressRequest::class);
});
