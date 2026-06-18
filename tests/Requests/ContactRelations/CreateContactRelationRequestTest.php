<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactRelations\ContactRelationDTO;
use CodebarAg\Bexio\Dto\ContactRelations\CreateEditContactRelationDTO;
use CodebarAg\Bexio\Dto\Contacts\CreateEditContactDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactRelations\CreateContactRelationRequest;
use CodebarAg\Bexio\Requests\Contacts\CreateContactRequest;
use CodebarAg\Bexio\Requests\Contacts\FetchAListOfContactsRequest;
use CodebarAg\Bexio\Requests\Users\FetchAuthenticatedUserRequest;
use Illuminate\Support\Str;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/ContactRelations/create-contact-relation.json';
    $contactFixturePath = __DIR__.'/../../Fixtures/Saloon/ContactRelations/create-contact-relation-contact.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($contactFixturePath);
    }

    Saloon::fake([
        CreateContactRelationRequest::class => MockResponse::fixture('ContactRelations/create-contact-relation'),
        CreateContactRequest::class => MockResponse::fixture('ContactRelations/create-contact-relation-contact'),
        FetchAListOfContactsRequest::class => MockResponse::fixture('Contacts/fetch-a-list-of-contacts'),
        FetchAuthenticatedUserRequest::class => MockResponse::fixture('Users/fetch-authenticated-user'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $user = $connector->send(new FetchAuthenticatedUserRequest)->dto();
    $existingContact = $connector->send(new FetchAListOfContactsRequest)->dto()->first();

    if (! $existingContact) {
        $this->markTestSkipped('No contact available to relate');
    }

    // Create one fresh contact so the relation to an existing one is never a duplicate.
    $newContact = $connector->send(new CreateContactRequest(
        new CreateEditContactDTO(
            user_id: $user->id,
            owner_id: $user->id,
            contact_type_id: 1,
            name_1: 'Relation '.Str::uuid(),
        )
    ))->dto();

    $response = $connector->send(new CreateContactRelationRequest(
        new CreateEditContactRelationDTO(
            contact_id: $existingContact->id,
            contact_sub_id: $newContact->id,
            description: 'This is a test',
        )
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(ContactRelationDTO::class);

    Saloon::assertSent(CreateContactRelationRequest::class);
})->group('contact-relations');
