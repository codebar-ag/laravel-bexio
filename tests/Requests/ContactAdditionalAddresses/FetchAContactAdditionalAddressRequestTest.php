<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Requests\ContactAdditionalAddresses\FetchAContactAdditionalAddressRequest;
use CodebarAg\Bexio\Requests\ContactRelations\FetchAContactRelationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        FetchAContactAdditionalAddressRequest::class => MockResponse::fixture('ContactAdditionalAddresses/fetch-a-contact-additional-address'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new FetchAContactAdditionalAddressRequest(contactId: 1, id: 10));

    $mockClient->assertSent(FetchAContactAdditionalAddressRequest::class);
});
