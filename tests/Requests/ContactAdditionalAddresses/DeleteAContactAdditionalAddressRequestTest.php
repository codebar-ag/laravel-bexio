<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactAdditionalAddresses\DeleteAContactAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        DeleteAContactAdditionalAddressRequest::class => MockResponse::fixture('ContactAdditionalAddresses/delete-a-contact-additional-address'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new DeleteAContactAdditionalAddressRequest(contactId: 1, id: 9));

    Saloon::assertSent(DeleteAContactAdditionalAddressRequest::class);
});
