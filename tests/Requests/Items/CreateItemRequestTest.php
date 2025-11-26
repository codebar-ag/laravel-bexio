<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\Items\CreateEditItemDTO;
use CodebarAg\Bexio\Dto\Items\ItemDTO;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Items\CreateItemRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    $fixturePath = __DIR__.'/../../Fixtures/Saloon/Items/create-item.json';

    if (shouldResetFixtures()) {
        unlink($fixturePath);
    }

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
            intern_code: 'TEST-'.time(),
            intern_name: 'Test Item'
        )
    ));

    expect($response->successful())->toBeTrue();
    expect($response->dto())->toBeInstanceOf(ItemDTO::class);

    Saloon::assertSent(CreateItemRequest::class);
})->group('items');
