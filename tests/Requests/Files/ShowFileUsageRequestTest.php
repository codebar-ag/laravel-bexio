<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Files\ShowFileUsageRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        //        ShowFileUsageRequest::class => MockResponse::fixture('Files/show-file-usage'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);
    $response = $connector->send(new ShowFileUsageRequest(
        id: 1,
    ));

    ray($response->dto());

    Saloon::assertSent(ShowFileUsageRequest::class);
})->skip('Not returning data.');
