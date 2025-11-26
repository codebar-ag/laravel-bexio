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
            article_type_id: 1,
            intern_code: 'ITEM-001',
            intern_name: 'Test Item'
        )
    ));

    Saloon::assertSent(CreateItemRequest::class);
});
