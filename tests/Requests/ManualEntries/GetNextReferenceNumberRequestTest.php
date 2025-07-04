<?php

use CodebarAg\Bexio\BexioConnector;
use CodebarAg\Bexio\Dto\OAuthConfiguration\ConnectWithToken;
use CodebarAg\Bexio\Requests\ManualEntries\GetNextReferenceNumberRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Saloon;

it('can perform the request', closure: function () {
    Saloon::fake([
        GetNextReferenceNumberRequest::class => MockResponse::fixture('ManualEntries/get-next-reference-number'),
    ]);

    $connector = new BexioConnector(new ConnectWithToken);

    $response = $connector->send(new GetNextReferenceNumberRequest);

    Saloon::assertSent(GetNextReferenceNumberRequest::class);
});
