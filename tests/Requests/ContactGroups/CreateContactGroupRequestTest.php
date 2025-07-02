<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactGroups\CreateEditContactGroupDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactGroups\CreateContactGroupRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CreateContactGroupRequest::class => MockResponse::fixture('ContactGroups/create-contact-group'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CreateContactGroupRequest(
        new CreateEditContactGroupDTO(
            name: 'Test',
        )
    ));

    Saloon::assertSent(CreateContactGroupRequest::class);
});
