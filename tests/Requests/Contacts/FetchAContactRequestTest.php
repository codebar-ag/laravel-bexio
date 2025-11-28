<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Contacts\FetchAContactRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAContactRequest::class => MockResponse::fixture('Contacts/fetch-a-contact'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAContactRequest(id: 1));

    Saloon::assertSent(FetchAContactRequest::class);
})->group('contacts');
