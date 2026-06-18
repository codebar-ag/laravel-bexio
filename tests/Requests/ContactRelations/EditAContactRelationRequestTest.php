<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactRelations\ContactRelationDTO;
use CodebarAg\Bexio\Dto\ContactRelations\CreateEditContactRelationDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactRelations\CreateContactRelationRequest;
use CodebarAg\Bexio\Requests\ContactRelations\EditAContactRelationRequest;
use CodebarAg\Bexio\Requests\ContactRelations\FetchAListOfContactRelationsRequest;
use CodebarAg\Bexio\Requests\Contacts\FetchAListOfContactsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    if (shouldResetFixtures()) {
        $base = __DIR__.'/../../Fixtures/Saloon/ContactRelations/';
        @unlink($base.'edit-contact-relation.json');
        @unlink($base.'fetch-a-list-of-contact-relations.json');
        @unlink($base.'create-contact-relation.json');
    }

    Saloon::fake([
        FetchAListOfContactRelationsRequest::class => MockResponse::fixture('ContactRelations/fetch-a-list-of-contact-relations'),
        FetchAListOfContactsRequest::class => MockResponse::fixture('Contacts/fetch-a-list-of-contacts'),
        CreateContactRelationRequest::class => MockResponse::fixture('ContactRelations/create-contact-relation'),
        EditAContactRelationRequest::class => MockResponse::fixture('ContactRelations/edit-contact-relation'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $contacts = $connector->send(new FetchAListOfContactsRequest)->dto();

    if ($contacts->count() < 2) {
        $this->markTestSkipped('At least two contacts are required to edit a contact relation.');
    }

    $contactId = $contacts->first()->id;
    $contactSubId = $contacts->skip(1)->first()->id;

    $relation = $connector->send(new FetchAListOfContactRelationsRequest)->dto()->first();

    if (! $relation) {
        $relation = $connector->send(new CreateContactRelationRequest(
            new CreateEditContactRelationDTO(
                contact_id: $contactId,
                contact_sub_id: $contactSubId,
                description: 'This is a test',
            )
        ))->dto();
    }

    $response = $connector->send(new EditAContactRelationRequest(
        $relation->id,
        new CreateEditContactRelationDTO(
            contact_id: $contactId,
            contact_sub_id: $contactSubId,
            description: 'This is a test edit',
        )
    ));

    Saloon::assertSent(EditAContactRelationRequest::class);

    expect($response->dto())->toBeInstanceOf(ContactRelationDTO::class)
        ->and($response->dto()->id)->toBe($relation->id);
});
