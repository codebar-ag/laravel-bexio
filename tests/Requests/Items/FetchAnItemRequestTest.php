<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Items\FetchAnItemRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        FetchAnItemRequest::class => MockResponse::fixture('Items/fetch-an-item'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new FetchAnItemRequest(article_id: 1));

    Saloon::assertSent(FetchAnItemRequest::class);
});
