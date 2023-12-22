<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Titles\CreateEditTitleDTO;
use CodebarAg\Bexio\Requests\Titles\EditATitleRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        EditATitleRequest::class => MockResponse::fixture('Titles/edit-a-title'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new EditATitleRequest(
        id: 4,
        data: new CreateEditTitleDTO(
            name: 'Test name edited',
        )
    ));

    $mockClient->assertSent(EditATitleRequest::class);
});
