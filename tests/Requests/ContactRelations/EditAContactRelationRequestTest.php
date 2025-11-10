<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\ContactRelations\CreateEditContactRelationDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ContactRelations\EditAContactRelationRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        EditAContactRelationRequest::class => MockResponse::fixture('ContactRelations/edit-contact-relation'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new EditAContactRelationRequest(
        2,
        new CreateEditContactRelationDTO(
            contact_id: 2,
            contact_sub_id: 1,
            description: 'This is a test edit',
        )
    ));

    Saloon::assertSent(EditAContactRelationRequest::class);
});
