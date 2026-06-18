<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\AdditionalAddresses\AdditionalAddressDTO;
use CodebarAg\Bexio\Dto\AdditionalAddresses\CreateEditAdditionalAddressDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\AdditionalAddresses\CreateAnAdditionalAddressRequest;
use CodebarAg\Bexio\Requests\AdditionalAddresses\EditAnAdditionalAddressRequest;
use CodebarAg\Bexio\Requests\Contacts\FetchAListOfContactsRequest;
use Illuminate\Support\Str;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/AdditionalAddresses/edit-an-additional-address.json';
    $createFixturePath = __DIR__.'/../../Fixtures/Saloon/AdditionalAddresses/edit-an-additional-address-create.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($createFixturePath);
    }

    Saloon::fake([
        EditAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/edit-an-additional-address'),
        CreateAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/edit-an-additional-address-create'),
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
            name: 'Edit '.Str::uuid(),
            name_addition: null,
            subject: 'Test subject',
            description: 'Test description',
            postcode: '12345',
            city: 'Test city',
            country_id: 1,
        )
    ))->dto();

    $response = $connector->send(new EditAnAdditionalAddressRequest(
        contactId: $contact->id,
        id: $created->id,
        data: new CreateEditAdditionalAddressDTO(
            name: 'Edited '.Str::uuid(),
            name_addition: null,
            subject: 'Test subject edited',
            description: 'Test description edited',
            postcode: '54321',
            city: 'Test city edited',
            country_id: 1,
        )
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(AdditionalAddressDTO::class);

    Saloon::assertSent(EditAnAdditionalAddressRequest::class);
})->group('additional-addresses');
