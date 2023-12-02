<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactGroups\CreateEditContactGroupDTO;
use CodebarAg\Bexio\Requests\ContactGroups\CreateContactGroupRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can get all tickets', closure: function () {
    $mockClient = new MockClient([
        CreateContactGroupRequest::class => MockResponse::fixture('ContactGroups/create-contact-group'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new CreateContactGroupRequest(
        new CreateEditContactGroupDTO(
            name: 'Test',
        )
    ));

    $mockClient->assertSent(CreateContactGroupRequest::class);
});
