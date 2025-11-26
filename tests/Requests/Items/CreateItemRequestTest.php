<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Items\CreateEditItemDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Items\CreateItemRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        CreateItemRequest::class => MockResponse::fixture('Items/create-item'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new CreateItemRequest(
        new CreateEditItemDTO(
            user_id: null,
            article_type_id: 1,
            contact_id: null,
            deliverer_code: null,
            deliverer_name: null,
            deliverer_description: null,
            intern_code: 'ITEM-001',
            intern_name: 'Test Item'
        )
    ));

    Saloon::assertSent(CreateItemRequest::class);
});
