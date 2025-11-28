<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Contacts\RestoreAContactRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        RestoreAContactRequest::class => MockResponse::fixture('Contacts/restore-a-contact'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new RestoreAContactRequest(id: 4));

    Saloon::assertSent(RestoreAContactRequest::class);
})->group('contacts');
