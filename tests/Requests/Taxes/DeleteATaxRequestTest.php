<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\Taxes\DeleteATaxRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        DeleteATaxRequest::class => MockResponse::fixture('Taxes/delete-a-tax'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new DeleteATaxRequest(id: 3));

    Saloon::assertSent(DeleteATaxRequest::class);
})->group('taxes');
