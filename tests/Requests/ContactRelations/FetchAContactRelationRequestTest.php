<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactRelations\ContactRelationDTO;
use CodebarAg\Bexio\Dto\ContactRelations\CreateEditContactRelationDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactRelations\CreateContactRelationRequest;
use CodebarAg\Bexio\Requests\ContactRelations\FetchAContactRelationRequest;
use CodebarAg\Bexio\Requests\ContactRelations\FetchAListOfContactRelationsRequest;
use CodebarAg\Bexio\Requests\Contacts\FetchAListOfContactsRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    if (shouldResetFixtures()) {
        $base = __DIR__.'/../../Fixtures/Saloon/ContactRelations/';
        @unlink($base.'fetch-a-contact-relation.json');
        @unlink($base.'fetch-a-list-of-contact-relations.json');
        @unlink($base.'create-contact-relation.json');
    }

    Saloon::fake([
        FetchAListOfContactRelationsRequest::class => MockResponse::fixture('ContactRelations/fetch-a-list-of-contact-relations'),
        FetchAListOfContactsRequest::class => MockResponse::fixture('Contacts/fetch-a-list-of-contacts'),
        CreateContactRelationRequest::class => MockResponse::fixture('ContactRelations/create-contact-relation'),
        FetchAContactRelationRequest::class => MockResponse::fixture('ContactRelations/fetch-a-contact-relation'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $relation = $connector->send(new FetchAListOfContactRelationsRequest)->dto()->first();

    if (! $relation) {
        $contacts = $connector->send(new FetchAListOfContactsRequest)->dto();

        if ($contacts->count() < 2) {
            $this->markTestSkipped('At least two contacts are required to create a contact relation.');
        }

        $relation = $connector->send(new CreateContactRelationRequest(
            new CreateEditContactRelationDTO(
                contact_id: $contacts->first()->id,
                contact_sub_id: $contacts->skip(1)->first()->id,
                description: 'This is a test',
            )
        ))->dto();
    }

    $response = $connector->send(new FetchAContactRelationRequest(id: $relation->id));

    Saloon::assertSent(FetchAContactRelationRequest::class);

    expect($response->dto())->toBeInstanceOf(ContactRelationDTO::class)
        ->and($response->dto()->id)->toBe($relation->id);
});
