<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Salutations\CreateEditSalutationDTO;
use CodebarAg\Bexio\Requests\Salutations\CreateASalutationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        CreateASalutationRequest::class => MockResponse::fixture('Salutations/create-a-salutation'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new CreateASalutationRequest(
        data: new CreateEditSalutationDTO(
            name: 'Test name',
        )
    ));

    $mockClient->assertSent(CreateASalutationRequest::class);
});
