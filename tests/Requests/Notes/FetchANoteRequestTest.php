<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Notes\CreateEditNoteDTO;
use CodebarAg\Bexio\Dto\Notes\NoteDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Notes\CreateANoteRequest;
use CodebarAg\Bexio\Requests\Notes\FetchANoteRequest;
use CodebarAg\Bexio\Requests\Users\FetchAuthenticatedUserRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAuthenticatedUserRequest::class => MockResponse::fixture('Users/fetch-authenticated-user'),
        CreateANoteRequest::class => MockResponse::fixture('Notes/create-a-note-for-fetch'),
        FetchANoteRequest::class => MockResponse::fixture('Notes/fetch-a-note'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $user = $connector->send(new FetchAuthenticatedUserRequest)->dto();

    $note = $connector->send(new CreateANoteRequest(
        data: new CreateEditNoteDTO(
            user_id: $user->id,
            event_start: now()->format('Y-m-d H:i:s'),
            subject: 'Some Subject',
        )
    ))->dto();

    $response = $connector->send(new FetchANoteRequest(
        id: $note->id,
    ));

    Saloon::assertSent(FetchANoteRequest::class);

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(NoteDTO::class);
});
