<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactGroups\CreateEditContactGroupDTO;
use CodebarAg\Bexio\Requests\ContactGroups\EditAContactGroupRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Http\Faking\MockClient;

it('can perform the request', closure: function () {
    $mockClient = new MockClient([
        EditAContactGroupRequest::class => MockResponse::fixture('ContactGroups/edit-contact-group'),
    ]);

    $connector = new BexioConnector;
    $connector->withMockClient($mockClient);

    $response = $connector->send(new EditAContactGroupRequest(
        2,
        new CreateEditContactGroupDTO(
            name: 'Test Edit',
        )
    ));

    $mockClient->assertSent(EditAContactGroupRequest::class);
});
