<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Items\CreateEditItemDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Items\EditAnItemRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        EditAnItemRequest::class => MockResponse::fixture('Items/edit-an-item'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new EditAnItemRequest(
        article_id: 1,
        data: new CreateEditItemDTO(
            user_id: null,
            article_type_id: 1,
            contact_id: null,
            deliverer_code: null,
            deliverer_name: null,
            deliverer_description: null,
            intern_code: 'ITEM-001',
            intern_name: 'Updated Item Name'
        )
    ));

    Saloon::assertSent(EditAnItemRequest::class);
});
