<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Contacts\ContactDTO;
use CodebarAg\Bexio\Dto\Contacts\CreateEditContactDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Contacts\CreateContactRequest;
use CodebarAg\Bexio\Requests\Users\FetchAuthenticatedUserRequest;
use Illuminate\Support\Str;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Contacts/create-contact.json';

    if (shouldResetFixtures()) {
        @unlink($fixturePath);
    }

    Saloon::fake([
        CreateContactRequest::class => MockResponse::fixture('Contacts/create-contact'),
        FetchAuthenticatedUserRequest::class => MockResponse::fixture('Users/fetch-authenticated-user'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $user = $connector->send(new FetchAuthenticatedUserRequest)->dto();

    $response = $connector->send(new CreateContactRequest(
        new CreateEditContactDTO(
            user_id: $user->id,
            owner_id: $user->id,
            contact_type_id: 1,
            name_1: 'Create '.Str::uuid(),
        )
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(ContactDTO::class);

    Saloon::assertSent(CreateContactRequest::class);
})->group('contacts');
