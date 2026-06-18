<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Contacts\ContactDTO;
use CodebarAg\Bexio\Dto\Contacts\CreateEditContactDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Contacts\CreateContactRequest;
use CodebarAg\Bexio\Requests\Contacts\EditAContactRequest;
use CodebarAg\Bexio\Requests\Users\FetchAuthenticatedUserRequest;
use Illuminate\Support\Str;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Contacts/edit-contact.json';
    $contactFixturePath = __DIR__.'/../../Fixtures/Saloon/Contacts/edit-contact-create.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($contactFixturePath);
    }

    Saloon::fake([
        EditAContactRequest::class => MockResponse::fixture('Contacts/edit-contact'),
        CreateContactRequest::class => MockResponse::fixture('Contacts/edit-contact-create'),
        FetchAuthenticatedUserRequest::class => MockResponse::fixture('Users/fetch-authenticated-user'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $user = $connector->send(new FetchAuthenticatedUserRequest)->dto();

    $contact = $connector->send(new CreateContactRequest(
        new CreateEditContactDTO(
            user_id: $user->id,
            owner_id: $user->id,
            contact_type_id: 1,
            name_1: 'Edit '.Str::uuid(),
        )
    ))->dto();

    $response = $connector->send(new EditAContactRequest(
        $contact->id,
        new CreateEditContactDTO(
            user_id: $user->id,
            owner_id: $user->id,
            contact_type_id: 1,
            name_1: 'Edited '.Str::uuid(),
            nr: $contact->nr,
        )
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(ContactDTO::class);

    Saloon::assertSent(EditAContactRequest::class);
})->group('contacts');
