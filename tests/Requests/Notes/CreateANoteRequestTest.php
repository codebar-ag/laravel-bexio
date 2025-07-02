<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Notes\CreateEditNoteDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Notes\CreateANoteRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        CreateANoteRequest::class => MockResponse::fixture('Notes/create-a-note'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $connector->withMockClient($mockClient);

    $response = $connector->send(new CreateANoteRequest(
        data: new CreateEditNoteDTO(
            user_id: 1,
            event_start: '2023-12-22 18:24:00',
            subject: 'Some Subject'
        )
    ));

    $mockClient->assertSent(CreateANoteRequest::class);
});
