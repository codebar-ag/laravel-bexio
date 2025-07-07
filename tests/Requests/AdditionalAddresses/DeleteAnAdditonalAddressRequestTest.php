<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\AdditionalAddresses\DeleteAnAdditionalAddressRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        DeleteAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/delete-an-additional-address'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new DeleteAnAdditionalAddressRequest(
        contactId: 1,
        id: 10
    ));

    Saloon::assertSent(DeleteAnAdditionalAddressRequest::class);
});
