<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Dto\Notes\CreateEditNoteDTO;
use CodebarAg\Bexio\Requests\Notes\EditANoteRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        EditANoteRequest::class => MockResponse::fixture('Notes/edit-a-note'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new EditANoteRequest(
        id: 3,
        data: new CreateEditNoteDTO(
            user_id: 1,
            event_start: '2023-12-22 18:24:00',
            subject: 'Some Subject Edit'
        )
    ));

    $mockClient->assertSent(EditANoteRequest::class);
});
