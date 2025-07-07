<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactGroups\CreateEditContactGroupDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactGroups\EditAContactGroupRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        EditAContactGroupRequest::class => MockResponse::fixture('ContactGroups/edit-contact-group'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new EditAContactGroupRequest(
        2,
        new CreateEditContactGroupDTO(
            name: 'Test Edit',
        )
    ));

    Saloon::assertSent(EditAContactGroupRequest::class);
});
