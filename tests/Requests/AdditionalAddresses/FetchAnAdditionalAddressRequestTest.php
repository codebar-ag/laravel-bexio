<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\AdditionalAddresses\AdditionalAddressDTO;
use CodebarAg\Bexio\Dto\AdditionalAddresses\CreateEditAdditionalAddressDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\AdditionalAddresses\CreateAnAdditionalAddressRequest;
use CodebarAg\Bexio\Requests\AdditionalAddresses\FetchAnAdditionalAddressRequest;
use CodebarAg\Bexio\Requests\Contacts\FetchAListOfContactsRequest;
use Illuminate\Support\Str;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/AdditionalAddresses/fetch-an-additional-addresses.json';
    $createFixturePath = __DIR__.'/../../Fixtures/Saloon/AdditionalAddresses/fetch-an-additional-address-create.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($createFixturePath);
    }

    Saloon::fake([
        FetchAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/fetch-an-additional-addresses'),
        CreateAnAdditionalAddressRequest::class => MockResponse::fixture('AdditionalAddresses/fetch-an-additional-address-create'),
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
            name: 'Fetch '.Str::uuid(),
            name_addition: null,
            subject: 'Test subject',
            description: 'Test description',
            postcode: '12345',
            city: 'Test city',
            country_id: 1,
        )
    ))->dto();

    $response = $connector->send(new FetchAnAdditionalAddressRequest(
        contactId: $contact->id,
        id: $created->id,
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(AdditionalAddressDTO::class);

    Saloon::assertSent(FetchAnAdditionalAddressRequest::class);
})->group('additional-addresses');
