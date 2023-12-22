<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Salutations\CreateEditSalutationDTO;
use CodebarAg\Bexio\Requests\Salutations\EditASalutationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        EditASalutationRequest::class => MockResponse::fixture('Salutations/edit-a-salutation'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new EditASalutationRequest(
        id: 5,
        data: new CreateEditSalutationDTO(
            name: 'Test name edited',
        )
    ));

    $mockClient->assertSent(EditASalutationRequest::class);
});
