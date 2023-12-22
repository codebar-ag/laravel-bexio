<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Titles\CreateEditTitleDTO;
use CodebarAg\Bexio\Requests\Titles\CreateATitleRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        CreateATitleRequest::class => MockResponse::fixture('Titles/create-a-title'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new CreateATitleRequest(
        data: new CreateEditTitleDTO(
            name: 'Test name',
        )
    ));

    $mockClient->assertSent(CreateATitleRequest::class);
});
