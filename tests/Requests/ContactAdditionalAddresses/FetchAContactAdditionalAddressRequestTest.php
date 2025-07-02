<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactAdditionalAddresses\FetchAContactAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAContactAdditionalAddressRequest::class => MockResponse::fixture('ContactAdditionalAddresses/fetch-a-contact-additional-address'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAContactAdditionalAddressRequest(contactId: 1, id: 10));

    Saloon::assertSent(FetchAContactAdditionalAddressRequest::class);
});
