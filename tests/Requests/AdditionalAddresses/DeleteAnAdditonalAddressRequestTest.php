<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\AdditionalAddresses\CreateEditAdditionalAddressDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\AdditionalAddresses\CreateAnAdditionalAddressRequest;
use CodebarAg\Bexio\Requests\AdditionalAddresses\DeleteAnAdditionalAddressRequest;
use CodebarAg\Bexio\Requests\Contacts\FetchAListOfContactsRequest;
use Illuminate\Support\Str;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/AdditionalAddresses/delete-an-additional-address.json';
    $createFixturePath = __DIR__.'/../../Fixtures/Saloon/AdditionalAddresses/delete-an-additional-address-create.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($createFixturePath);
    }

    Saloon::fake([
        DeleteAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/delete-an-additional-address'),
        CreateAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/delete-an-additional-address-create'),
        FetchAListOfContactsRequest::class => MockResponse::fixture('Contacts/fetch-a-list-of-contacts'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $contact = $connector->send(new FetchAListOfContactsRequest)->dto()->first();

    if (! $contact) {
        $this->markTestSkipped('No contact available to scope additional address');
    }

    $created = $connector->send(new CreateAnAdditionalAddressRequest(
        contactId: $contact->id,
        data: new CreateEditAdditionalAddressDTO(
            name: 'Delete '.Str::uuid(),
            name_addition: null,
            subject: 'Test subject',
            description: 'Test description',
            postcode: '12345',
            city: 'Test city',
            country_id: 1,
        )
    ))->dto();

    $response = $connector->send(new DeleteAnAdditionalAddressRequest(
        contactId: $contact->id,
        id: $created->id,
    ));

    expect($response->successful())->toBeTrue();

    Saloon::assertSent(DeleteAnAdditionalAddressRequest::class);
})->group('additional-addresses');
