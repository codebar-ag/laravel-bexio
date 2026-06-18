<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Contacts\CreateEditContactDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Contacts\CreateContactRequest;
use CodebarAg\Bexio\Requests\Contacts\DeleteAContactRequest;
use CodebarAg\Bexio\Requests\Contacts\RestoreAContactRequest;
use CodebarAg\Bexio\Requests\Users\FetchAuthenticatedUserRequest;
use Illuminate\Support\Str;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Contacts/restore-a-contact.json';
    $contactFixturePath = __DIR__.'/../../Fixtures/Saloon/Contacts/restore-a-contact-create.json';
    $deleteFixturePath = __DIR__.'/../../Fixtures/Saloon/Contacts/restore-a-contact-delete.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
        @unlink($contactFixturePath);
        @unlink($deleteFixturePath);
    }

    Saloon::fake([
        RestoreAContactRequest::class => MockResponse::fixture('Contacts/restore-a-contact'),
        CreateContactRequest::class => MockResponse::fixture('Contacts/restore-a-contact-create'),
        DeleteAContactRequest::class => MockResponse::fixture('Contacts/restore-a-contact-delete'),
        FetchAuthenticatedUserRequest::class => MockResponse::fixture('Users/fetch-authenticated-user'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $user = $connector->send(new FetchAuthenticatedUserRequest)->dto();

    $contact = $connector->send(new CreateContactRequest(
        new CreateEditContactDTO(
            user_id: $user->id,
            owner_id: $user->id,
            contact_type_id: 1,
            name_1: 'Restore '.Str::uuid(),
        )
    ))->dto();

    $connector->send(new DeleteAContactRequest(id: $contact->id));

    $response = $connector->send(new RestoreAContactRequest(id: $contact->id));

    expect($response->successful())->toBeTrue();

    Saloon::assertSent(RestoreAContactRequest::class);
})->group('contacts');
