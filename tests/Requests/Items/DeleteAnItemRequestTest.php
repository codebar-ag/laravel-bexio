<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Items\DeleteAnItemRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        DeleteAnItemRequest::class => MockResponse::fixture('Items/delete-an-item'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new DeleteAnItemRequest(article_id: 1));

    Saloon::assertSent(DeleteAnItemRequest::class);
});
